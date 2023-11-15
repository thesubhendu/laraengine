<?php

namespace App\Blueprint\Generators;

use Blueprint\Blueprint;
use \Blueprint\Generators\ModelGenerator as ParentModelGenerator;
use Blueprint\Contracts\Model;

class ModelGenerator extends ParentModelGenerator
{

    protected function getPath(Model $model)
    {
        $path = str_replace('\\', '/', Blueprint::relativeNamespace($model->fullyQualifiedClassName()));

        return sprintf('%s/%s.php', config('blueprint.base_path').'/'.Blueprint::appPath(), $path);
    }

}
