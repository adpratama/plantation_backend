<?php

namespace App\Imports;

use App\Models\PengajuanDanaindonesiana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengajuanDanaindonesianaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        PengajuanDanaindonesiana::where('id', $row['id'])->update([
            'status' => $row['status']
        ]);
    }
}
