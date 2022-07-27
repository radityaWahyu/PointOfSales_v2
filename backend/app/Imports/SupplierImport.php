<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{

    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Supplier([
            'id' => Str::Uuid(),
            'name' => $row['nama_pemasok'],
            'phone' => $row['telepon'],
            'address' => $row['alamat'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_pemasok' => 'required',
            'telepon' => 'required|numeric',
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
