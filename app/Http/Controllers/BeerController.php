<?php

namespace App\Http\Controllers;

use App\Services\PunkApiService;
use Illuminate\Http\Request;

class BeerController extends Controller
{
    public function index()
    {
        $service = new PunkApiService();
        return $service->getBeers()->json();
    }
}
