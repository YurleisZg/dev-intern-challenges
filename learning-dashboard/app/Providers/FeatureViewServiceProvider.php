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

        $groups = [
            'Elkin' => 'elkin',
            'Yurleis' => 'yurleis',
        ];

       foreach ($groups as $groupDir => $groupNs) {
            foreach (glob("$featuresPath/$groupDir/*/Views", GLOB_ONLYDIR) as $viewPath) {
                $featureFolder = basename(dirname($viewPath));
                $featureNs = Str::kebab($featureFolder);

                $this->loadViewsFrom($viewPath, "{$groupNs}-{$featureNs}");
            }
    }
}
}
