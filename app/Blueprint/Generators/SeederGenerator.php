<?php

namespace App\Blueprint\Generators;

use \Blueprint\Generators\SeederGenerator as ParentSeederGenerator;
use Blueprint\Contracts\Model as BlueprintModel;

class SeederGenerator extends ParentSeederGenerator
{

    protected function getPath(BlueprintModel $blueprintModel)
    {
        $path = $blueprintModel->name();
        if ($blueprintModel->namespace()) {
            $path = str_replace('\\', '/', $blueprintModel->namespace()) . '/' . $path;
        }

        return config('blueprint.base_path') . '/' . 'database/seeders/' . $path . 'Seeder.php';
    }

}
