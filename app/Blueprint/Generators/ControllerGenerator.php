<?php

namespace App\Blueprint\Generators;

use Blueprint\Blueprint;
use Blueprint\Contracts\Model;
use \Blueprint\Generators\ControllerGenerator as ParentControllerGenerator;

class ControllerGenerator extends ParentControllerGenerator
{

    protected function getPath(Model $model)
    {
        $path = str_replace('\\', '/', Blueprint::relativeNamespace($model->fullyQualifiedClassName()));

        return sprintf('%s/%s.php', config('blueprint.base_path') . '/' . Blueprint::appPath(), $path);
    }

}
