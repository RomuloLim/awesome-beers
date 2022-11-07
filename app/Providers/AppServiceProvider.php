<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Http::macro('punkApi', function (){
            return Http::withHeaders([
                'Accept' => 'application/json'
            ])->baseUrl(config('punkapi.url'))
            ->retry(3, 100);
        });
    }
}
