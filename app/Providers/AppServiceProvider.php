<?php

namespace App\Providers;

use App\Lib\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton('homestead_config', function() {
            return Config::get();
        });

        app()->singleton('yaml', function () {
            return Yaml::parseFile(app('homestead_config')['path'].'/Homestead.yaml');
        });
    }
}
