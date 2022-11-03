<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PunkApiService
{
    public function getBeers()
    {
       return Http::get('https://api.punkapi.com/v2/beers');
    }
}
