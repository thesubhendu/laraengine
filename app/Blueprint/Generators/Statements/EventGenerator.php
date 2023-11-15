<?php

namespace App\Blueprint\Generators\Statements;

use Blueprint\Blueprint;
use \Blueprint\Generators\Statements\EventGenerator as ParentEventGenerator;

class EventGenerator extends ParentEventGenerator
{
    protected function getStatementPath(string $name)
    {
        return config('blueprint.base_path') . '/' . Blueprint::appPath() . '/Events/' . $name . '.php';
    }
}
