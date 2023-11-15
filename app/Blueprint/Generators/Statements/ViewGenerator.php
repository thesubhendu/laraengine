<?php

namespace App\Blueprint\Generators\Statements;

use \Blueprint\Generators\Statements\ViewGenerator as ParentViewGenerator;

class ViewGenerator extends ParentViewGenerator
{
    protected function getStatementPath(string $view)
    {
        return config('blueprint.base_path') . '/' . 'resources/views/' . str_replace('.', '/', $view) . '.blade.php';
    }

}
