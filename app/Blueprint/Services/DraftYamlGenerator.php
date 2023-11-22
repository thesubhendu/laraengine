<?php

namespace App\Blueprint\Services;

use App\Models\Crud;
use Symfony\Component\Yaml\Yaml;

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
        $cruds = $this->project->cruds;

        $yamlArray = [];

        foreach ($cruds as $crud) {
            $modelName = \Str::studly(\Str::singular($crud->name));

            $tableDefinition = $this->tableDefinition($crud);
            if(empty($tableDefinition)){
                continue;
            }
            $yamlArray['models'][$modelName] = $tableDefinition;

//            todo handle controller logic
            if (!empty($crud->controllers) && !empty($crud->controllers['type'])) {
                $controllerSettings = $crud->controllers;
                $controllerType = $controllerSettings['type'];

                $yamlArray['controllers'][\Str::studly($crud->name)]['resource'] = $controllerType;
            }
        }

        $modelNames = $cruds->pluck('name');
        $yamlArray['seeders'] = implode(',', $modelNames->toArray());

        $yamlContent = Yaml::dump($yamlArray, 999);
        $draftFile = $this->project->generatedCodeDirectoryPath() . '/draft.yaml';
        file_put_contents($draftFile, $yamlContent, 999);

        return $draftFile;
    }

    protected function tableDefinition(Crud $crud): array
    {
        $columns = $crud->blueprint;

        $tableDefinition = [];

        foreach ($columns as $index => $column) {
            $tableDefinition[$column['name']] = $this->getColumnDefinition($column);
        }

        if ($crud->relations) {
            $relationships = $this->relationships($crud->relations);
            if(!empty($relationships)){
                $tableDefinition['relationships'] = $relationships;
            }
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
            if(empty($relation['type']) || empty($relation['model'])){
                continue;
            }
            $relationName = $relation['type'];
            $with = $relation['model'];

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

        //todo extend entering name of table eg uid: id foreign:users.id
        if (!empty($column['foreign'])) {
            $definition .= ' foreign';
        }

        if (!empty($column['nullable'])) {
            $definition .= ' nullable';
        }

        if (!empty($column['unique'])) {
            $definition .= ' unique';
        }

        if (!empty($column['index'])) {
            $definition .= ' index';
        }

//        dd($definition);


        return $definition;
    }

}
