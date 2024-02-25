<?php

namespace App\Blueprint\Services;

use Blueprint\Builder;
use App\Models\Project;
use Blueprint\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class CodeGenerator
{
    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function generate()
    {
        //todo auto detect blueprint installed
        if(!$this->project->blueprint_installed_at){
            Process::path($this->project->path)->run('composer require --dev laravel-shift/blueprint');
            $this->project->blueprint_installed_at = now();
            $this->project->save();
        }

       (new DraftYamlGenerator($this->project))->generate();
        Process::path($this->project->path)->run('php artisan blueprint:build -m'); //-m overwrite migrations
    }

}
