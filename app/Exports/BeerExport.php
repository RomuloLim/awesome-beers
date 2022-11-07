<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BeerExport implements FromCollection, WithHeadings
{
    public function __construct(
        private array $exportData
    ){}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return collect($this->exportData);
    }

    public function headings(): array
    {
       return array_keys($this->exportData[0]);
    }
}
