<?php

namespace App\Blueprint\Generators\Statements;

use \Blueprint\Generators\Statements\ResourceGenerator as ParentResourceGenerator;

class ResourceGenerator extends ParentResourceGenerator
{

    protected function getStatementPath(string $name)
    {
        return config('blueprint.base_path') . '/' . Blueprint::appPath() . '/Http/Resources/' . $name . '.php';
    }

}
