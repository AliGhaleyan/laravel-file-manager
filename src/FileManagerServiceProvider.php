<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfig();
        $this->registerMigrations();
        $this->registerRoutes();
    }

    public function register()
    {
        //
    }

    public function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/filemanager.php' => config_path('filemanager.php')
        ],'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/filemanager.php','filemanager');
    }

    public function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $this->publishes([
            __DIR__ . '/Migrations/' => database_path('migrations')
        ], 'migrations');
    }

    protected function registerRoutes()
    {
        Route::prefix('/api/filemanager')
            ->namespace('AliGhale\FileManager\Controllers')
            ->group(__DIR__ . "/Routes/api.php");
    }
}
