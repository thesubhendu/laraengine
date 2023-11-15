<?php

namespace App\Blueprint\Generators;

use Blueprint\Tree;

use \Blueprint\Generators\RouteGenerator as ParentRouteGenerator;

class RouteGenerator extends ParentRouteGenerator
{

    public function output(Tree $tree): array
    {
        if (empty($tree->controllers())) {
            return [];
        }

        $routes = ['api' => '', 'web' => ''];

        /**
         * @var \Blueprint\Models\Controller $controller
         */
        foreach ($tree->controllers() as $controller) {
            $type = $controller->isApiResource() ? 'api' : 'web';
            $routes[$type] .= PHP_EOL . PHP_EOL . $this->buildRoutes($controller);
        }

        $paths = [];

        foreach (array_filter($routes) as $type => $definitions) {
            $path = config('blueprint.base_path') . '/'.'routes/' . $type . '.php';
            $this->filesystem->append($path, $definitions . PHP_EOL);
            $paths[] = $path;
        }

        return ['updated' => $paths];
    }

}
