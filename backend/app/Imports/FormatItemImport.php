<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class FormatItemImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{

    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Item([
            'id' => Str::Uuid(),
            'name' => $row['nama_barang'],
            'barcode' => $row['barcode'],
            'category_id' => $row['id_kategori'],
            'unit_id' => $row['id_satuan'],
            'purchase_price' => $row['harga_beli'],
            'selling_price' => $row['harga_jual'],
            'min_stock' => $row['min_stok'],
            'first_stock' => $row['stok_awal'],
            'stock' => $row['stok_awal']
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required',
            'barcode' => 'required|integer',
            'id_kategori' => 'required',
            'id_satuan' => 'required',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'min_stock' => 'required|integer',
            'stok_awal' => 'required|integer'
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
