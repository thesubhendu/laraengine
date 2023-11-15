<?php

namespace App\Blueprint\Generators;

use Blueprint\Contracts\Model as BlueprintModel;

use \Blueprint\Generators\FactoryGenerator as ParentFactoryGenerator;

class FactoryGenerator extends ParentFactoryGenerator
{

    protected function getPath(BlueprintModel $blueprintModel)
    {
        $path = $blueprintModel->name();
        if ($blueprintModel->namespace()) {
            $path = str_replace('\\', '/', $blueprintModel->namespace()) . '/' . $path;
        }

        return config('blueprint.base_path') . '/' . 'database/factories/' . $path . 'Factory.php';
    }


}
