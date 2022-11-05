<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Services\PunkApiService;
use Maatwebsite\Excel\Facades\Excel;

class BeerController extends Controller
{
    public function __construct(
        protected PunkApiService $service
    ){}

    public function index(BeerRequest $request)
    {
        return $this->service->getBeers(...$request->validated());
    }

    public function export(BeerRequest $request)
    {
        $beers = $this->service->getBeers(...$request->validated());

        $filteredBeers = collect($beers)->map(function($item, $key){
            return collect($item)->only(['name', 'tagline', 'first_brewed', 'description']);
        })->toArray();

        Excel::store(
            new BeerExport($filteredBeers),
            'aoba.xlsx',
            's3');

        return 'relatorio criado';
    }
}
