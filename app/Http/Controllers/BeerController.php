<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
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
        $fileName = 'exportedBeers_'.Carbon::now()->toISOString().'.xlsx';

        ExportJob::withChain([
            new SendExportEmailJob(Auth::user(), $fileName),
            new StoreExportDataJob(Auth::user(), $fileName),
        ])->dispatch($request->validated(), $fileName);

        return response()->json('relatório criado');
    }
}
