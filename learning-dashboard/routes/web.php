<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
$FeaturesPath = app_path('Features');


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
