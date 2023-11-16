<?php

namespace App\Blueprint\Services;

use App\Models\Project;

class DraftYamlGenerator
{
    public function __construct(
        public Project $project
    )
    {
    }

    public function generate()
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

        $yamlContent = $yaml->dump($yamlArray, 999);
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
