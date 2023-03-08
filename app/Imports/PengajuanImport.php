<?php

namespace App\Imports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengajuanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Pengajuan::where('id', $row['id'])->update([
            'status' => $row['status']
        ]);
    }
}
