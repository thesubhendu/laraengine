<?php

namespace App\Blueprint\Services;

use Blueprint\Builder;
use App\Models\Project;
use Blueprint\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class CodeGenerator
{
    public $project;
    public $workingDirPath;

    public function __construct(Project $project)
    {
        $this->project = $project;

        $this->workingDirPath = $project->generatedCodeDirectoryPath();

        config(['blueprint.base_path' => $project->generatedCodeDirectoryName()]);
    }

    public function generate()
    {
        $this->prepareDirectory();
       (new DraftYamlGenerator($this->project))->generate();
        $draftFile = $this->workingDirPath.'/draft.yaml';

        Artisan::call('blueprint:build ' . $draftFile);

        return $this->zipAndDownload($this->project->zipName());
    }

    protected function zipAndDownload($zipName)
    {
        $zip = new \ZipArchive();
        $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->workingDirPath));
        foreach ($files as $name => $file) {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();

                $relativePath = substr($filePath, strlen($this->workingDirPath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return response()->download($zipName);
    }

    /**
     * @return void
     */
    public function prepareDirectory(): void
    {
        //remove .blueprint file
//        File::delete(public_path('..blueprint'));
        File::cleanDirectory($this->workingDirPath);
        $laravelDefaultCode = public_path('laravelstub'); //todo load from config ideal
        File::copyDirectory($laravelDefaultCode, $this->workingDirPath);
    }


}
