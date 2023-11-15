<?php

namespace App\Blueprint\Generators\Statements;

use Blueprint\Blueprint;
use \Blueprint\Generators\Statements\JobGenerator as ParentJobGenerator;

class JobGenerator extends ParentJobGenerator
{
    protected function getStatementPath(string $name)
    {
        return config('blueprint.base_path') . '/' . Blueprint::appPath() . '/Jobs/' . $name . '.php';
    }
}
