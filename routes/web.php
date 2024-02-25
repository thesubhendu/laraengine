<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects', \App\Livewire\LaravelProjects::class)->name('projects.index');
Route::get('/project/{project}', \App\Livewire\ProjectSingle::class)->name('projects.show');

Route::get('/project/{project}/crud/{crud?}', \App\Livewire\Crud::class)->name('projects.crud');
