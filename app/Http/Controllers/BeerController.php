<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Mail\ExportEmail;
use App\Models\Export;
use App\Services\PunkApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

        $fileName = 'exportedBeers_'.Carbon::now()->toISOString().'.xlsx';

        Excel::store(
            new BeerExport($filteredBeers),
            $fileName,
            's3');

        Mail::to('test@gmail.com')
                ->send(new ExportEmail($fileName));

        Export::create([
            'file_name' => $fileName,
            'user_id' => Auth::user()->id
        ]);

        return response()->json('relatório criado');
    }
}
