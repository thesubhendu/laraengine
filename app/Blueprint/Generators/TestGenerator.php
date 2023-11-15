<?php

namespace App\Blueprint\Generators;

use Blueprint\Blueprint;
use Blueprint\Contracts\Model;
use \Blueprint\Generators\TestGenerator as ParentTestGenerator;

class TestGenerator extends ParentTestGenerator
{

    protected function getPath(Model $model)
    {
        $path = str_replace('\\', '/', Blueprint::relativeNamespace($model->fullyQualifiedClassName()));

        return config('blueprint.base_path') . '/' . 'tests/Feature/' . $path . 'Test.php';
    }

}
