<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class FormatImportBarang implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [
            new FormatBarang(),
            new ExportKategori(),
            new ExportSatuan()
        ];

        return $sheets;
    }
}
