<?php

namespace App\Jobs;

use App\Exports\BeerExport;
use App\Services\PunkApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected array $data,
        protected string $fileName,
        protected PunkApiService $service = new PunkApiService,
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $beers = $this->service->getBeers(...$this->data);

        $filteredBeers = array_map(function($item){
            return collect($item)->only([
                'name',
                'tagline',
                'first_brewed',
                'description'
            ])->toArray();
        }, $beers);


        Excel::store(
            new BeerExport($filteredBeers),
            $this->fileName,
            's3');
    }
}
