<?php

namespace App\Providers;

use App\Models\User;
use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Menu\Menu;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        $anyUser = User::first();
        if(!$anyUser){
            $loggedInUser = User::factory()->create([
                'name'=>'Admin',
                'email'=>'admin@admin.com',
            ]);
            auth()->loginUsingId($loggedInUser->id);
        }else if($anyUser){
            auth()->loginUsingId($anyUser->id);
        }

        Menu::new()
            ->appMenu()
            ->submenu('View', Menu::new()
                ->toggleFullscreen()
                ->separator()
                ->toggleDevTools()
            )
            ->editMenu()
            ->register();

        Window::open()
            ->route('projects')
            ->width(900)
            ->height(800)
            ->showDevTools(false)
            ->rememberState()
        ;
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}

