<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FormatSupplier implements WithHeadings, WithTitle
{
    use Exportable;
    public function headings(): array
    {
        return [
            'NAMA_PEMASOK',
            'TELEPON',
            'ALAMAT',
        ];
    }

    public function title(): string
    {
        return 'FORMAT_IMPORT';
    }
}
