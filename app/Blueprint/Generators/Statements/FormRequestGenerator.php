<?php

namespace App\Blueprint\Generators\Statements;

use Blueprint\Models\Controller;
use Blueprint\Blueprint;
use \Blueprint\Generators\Statements\FormRequestGenerator as ParentFormRequestGenerator;

class FormRequestGenerator extends ParentFormRequestGenerator
{
    protected function getStatementPath(Controller $controller, string $name)
    {
        return config('blueprint.base_path') . '/' . Blueprint::appPath() . '/Http/Requests/' . ($controller->namespace() ? $controller->namespace() . '/' : '') . $name . '.php';
    }
}
