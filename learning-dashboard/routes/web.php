<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
$FeaturesPath = app_path('Features');

Route::get('/', WelcomeController::class)->name('home');

Route::prefix('Elkin')
    ->as('elkin.')
    ->group(function () {
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');
        
        Route::prefix('challenges')
            ->as('challenges.')
            ->group(function () {

            foreach (glob(app_path('Features/Elkin/*/routes.php')) as $routeFile) {

                $featureName = basename(dirname($routeFile));
                $slug = Str::kebab($featureName);

                Route::prefix($slug)
                    ->as($slug . '.')
                    ->group(function () use ($routeFile) {
                        require $routeFile;
                    });
            }
        });
    });

Route::prefix('Yurleis')
    ->as('yurleis.')
    ->group(function () {
        Route::get('/',[DashboardController::class,'indexYurleis'])->name('dashboardYurleis');
        Route::prefix('challenges')
            ->as('challenges.')
            ->group(function () {

            foreach (glob(app_path('Features/Yurleis/*/routes.php')) as $routeFile) {

                $featureName = basename(dirname($routeFile));
                $slug = Str::kebab($featureName);

                Route::prefix($slug)
                    ->as($slug . '.')
                    ->group(function () use ($routeFile) {
                        require $routeFile;
                    });
            }
        });
    });
