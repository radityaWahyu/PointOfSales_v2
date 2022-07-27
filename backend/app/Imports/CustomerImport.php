<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{

    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Customer([
            'id' => Str::Uuid(),
            'name' => $row['nama_pelanggan'],
            'phone' => $row['telepon'],
            'address' => $row['alamat'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_pelanggan' => 'required',
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
