<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ItemImport implements WithMultipleSheets
{

    public $itemImport;

    public function __construct()
    {
        $this->itemImport = new FormatItemImport();
    }

    public function sheets(): array
    {
        return [
            'FORMAT_IMPORT' => $this->itemImport
        ];
    }

    public function getRowCount(): int
    {
        return $this->itemImport->getRowCount();
    }
}
