<?php

namespace App\Observers;

use App\Models\Crud;
use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        //add user to crud
        Crud::create([
            'name'=>'User',
            'project_id'=> $project->id,
            'blueprint'=>[
                ['name'=>'name', 'type'=>'string'],
                ['name'=>'email','type'=>'string', 'unique'=>'true'],
                ['name'=>'email_verified_at','type'=>'timestamp', 'nullable'=>'true'],
                ['name'=>'password','type'=>'string'],
                ['name'=>'remember_token','type'=>'string', 'nullable'=>'true'],
            ]
        ]);

    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
