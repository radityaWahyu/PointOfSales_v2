<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FormatBarang implements WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'NAMA_BARANG',
            'BARCODE',
            'ID_KATEGORI',
            'ID_SATUAN',
            'HARGA_BELI',
            'HARGA_JUAL',
            'MIN_STOK',
            'STOK_AWAL'
        ];
    }

    public function title(): string
    {
        return 'FORMAT_IMPORT';
    }
}
