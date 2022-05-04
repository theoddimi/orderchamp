<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
//        $this->app->singleton(App\Models\Guest::class, function () {
//            $guest =  new App\Models\Guest();
//            return $guest->save();
//        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
       
    }

}
