<?php

namespace App\Blueprint\Generators\Statements;

use Blueprint\Blueprint;
use \Blueprint\Generators\Statements\MailGenerator as ParentMailGenerator;

class MailGenerator extends ParentMailGenerator
{
    protected function getStatementPath(string $name)
    {
        return config('blueprint.base_path') . '/' . Blueprint::appPath() . '/Mail/' . $name . '.php';
    }

}
