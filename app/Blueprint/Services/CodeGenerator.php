<?php

namespace App\Blueprint\Services;

use Blueprint\Blueprint;
use Blueprint\Builder;
use Illuminate\Support\Facades\File;
use App\Models\Project;

class CodeGenerator
{
    public $project;
    public $workingDirPath;
    public $zipName;

    public function __construct(Project $project)
    {
        $projectName = \Str::snake($project->name);
        $workingDir = 'generated_code/' . \Str::snake($project->user->name) . '/' . $projectName;
        $workingDirPath = public_path($workingDir);

        $this->project = $project;
        $this->workingDirPath = $workingDirPath;
        $this->zipName = $workingDirPath . '/' . $projectName . '.zip';

        config(['blueprint.base_path' => $workingDir]);
        config(['blueprint.laravel_version' => $project->laravel_version]);

        if ($project->laravel_version >= 8) {
            config(['blueprint.models_namespace' => 'Models']);
        }
    }

    public function generate()
    {
        File::cleanDirectory($this->workingDirPath);
        $laravelDefaultCode = __DIR__ . '/../../assets/laravel/' . $this->project->laravel_version;
        File::copyDirectory($laravelDefaultCode, $this->workingDirPath);

        $draftFile = $this->generateDraftContent($this->project);

        $blueprint = resolve(Blueprint::class);
        resolve(Builder::class)->execute($blueprint, $draftFile);

        return $this->zipAndDownload();
    }

    protected function zipAndDownload()
    {
        $zip = new \ZipArchive();
        $zip->open($this->zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->workingDirPath));
        foreach ($files as $name => $file) {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();

                $relativePath = substr($filePath, strlen($this->workingDirPath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return response()->download($this->zipName);
    }

    private function generateDraftContent()
    {
        $yaml = app('syaml');

        $cruds = $this->project->cruds;

        $yamlArray = [];

        foreach ($cruds as $crud) {
            $modelName = \Str::studly(\Str::singular($crud->name));

            $yamlArray['models'][$modelName] = $this->tableDefinition($crud);

            if (!empty($crud->blueprint['controller']['name'])) {

                $controllerType = 'web';

                if (!empty($crud->blueprint['controller']['type'])) {
                    $controllerType = $crud->blueprint['controller']['type'];
                }

                $yamlArray['controllers'][\Str::studly($crud->blueprint['controller']['name'])]['resource'] = $controllerType;
            }
        }

        // dd('ymlarr',$yamlArray);
        // dd($yamlArray['models']['Series']);
        $yamlContent = $yaml->dump($yamlArray, 999);
        // dd($yamlContent);
        // $draftFile = base_path('draft.yaml');
        $draftFile = $this->workingDirPath . '/draft.yaml';

        file_put_contents($draftFile, $yamlContent, 999);

        return $draftFile;
    }

    protected function tableDefinition($crud): array
    {
        $blueprint = $crud->blueprint;
        $columns = $blueprint['columns'];
        $tableDefinition = [];

        foreach ($columns as $index => $column) {
            $tableDefinition[$column['name']] = $this->getColumnDefinition($column);
        }

        if ($crud->relations->isNotEmpty()) {
            $tableDefinition['relationships'] = $this->relationships($crud->relations);
        }

        return $tableDefinition;
    }

    protected function relationships($relations): array
    {
        $availableRelations = ['hasOne', 'hasMany', 'belongsToMany'];
        $relationsDefinitions = [];

        $hasMany = [];
        $hasOne = [];
        $belongsToMany = [];

        foreach ($relations as  $relation) {
            $relationName = $relation->type;
            $with = $relation->with;

            if (in_array($relationName, $availableRelations)) {
                $$relationName[] = $with;
            }
        }

        foreach ($availableRelations as $rel) {
            if (!empty($$rel)) {
                $relationsDefinitions[$rel] = implode(', ', $$rel);
            }
        }

        return $relationsDefinitions;
    }



    private function getColumnDefinition($column): string
    {

        $definition = $column['type'];

        if (!empty($column['length'])) {
            $definition .= ':' . $column['length'];
        }

        if (!empty($column['nullable']) && $column['nullable'] == 'nullable') {
            $definition .= ' nullable';
        }

        if (!empty($column['unique']) && $column['unique'] == 'unique') {
            $definition .= ' unique';
        }

        return $definition;
    }

}
