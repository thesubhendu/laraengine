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

            $yamlArray['models'][$modelName] = $this->tableDefinition($crud);

//            todo handle controller logic
//            if (!empty($crud->blueprint['controller']['name'])) {
//
//                $controllerType = 'web';
//
//                if (!empty($crud->blueprint['controller']['type'])) {
//                    $controllerType = $crud->blueprint['controller']['type'];
//                }
//
//                $yamlArray['controllers'][\Str::studly($crud->blueprint['controller']['name'])]['resource'] = $controllerType;
//            }
        }

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

//        todo add relations

//        if ($crud->relations->isNotEmpty()) {
//            $tableDefinition['relationships'] = $this->relationships($crud->relations);
//        }

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
