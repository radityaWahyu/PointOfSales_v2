<?php

namespace App\Exports;


use App\Models\Unit;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportSatuan implements FromView,  WithTitle
{
    use Exportable;

    public function view(): View
    {
        return view('exports.unit_export', [
            'rows' => Unit::get()
        ]);
    }

    public function title(): string
    {
        return 'REF_SATUAN';
    }
}
