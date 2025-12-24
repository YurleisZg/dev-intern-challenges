<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FeatureViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $featuresPath = app_path('Features');
        foreach (glob("$featuresPath/*/Views", GLOB_ONLYDIR) as $viewPath) {
            $featureFolder = basename(dirname($viewPath));
            $namespace = Str::kebab($featureFolder);

            $this->loadViewsFrom($viewPath, $namespace);
        }
    }
}
