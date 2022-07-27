<?php

namespace App\Exports;

use App\Models\Category;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;


class ExportKategori implements FromView, WithTitle
{
    use Exportable;

    public function view(): View
    {
        return view('exports.category_export', [
            'rows' => Category::get()
        ]);
    }

    public function title(): string
    {
        return 'REF_KATEGORI';
    }
}
