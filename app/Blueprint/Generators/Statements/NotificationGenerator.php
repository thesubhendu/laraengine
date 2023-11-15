<?php

namespace App\Blueprint\Generators\Statements;

use Blueprint\Blueprint;
use \Blueprint\Generators\Statements\NotificationGenerator as ParentNotificationGenerator;

class NotificationGenerator extends ParentNotificationGenerator
{
    protected function getStatementPath(string $name)
    {
        return config('blueprint.base_path') . '/' . Blueprint::appPath() . '/Notification/' . $name . '.php';
    }

}
