<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class UserExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $periode_id;
    public $program_id;
    function __construct ($periode_id, $program_id) {
        $this->periode_id  = $periode_id;
        $this->program_id = $program_id;
    }
    public function collection()
    {
        return DB::table('users')->leftJoin('periode', 'users.periode_id', '=', 'periode.id')->leftJoin('program', 'users.program_id', '=',  'program.id')
        ->where([['users.periode_id', $this->periode_id], ['users.program_id', $this->program_id]])
        ->orderBy('users.updated_at', 'desc')
        ->select('users.id', 'users.name', 'users.email', 'periode.tahun', 'program.nama as program', 'users.role', 'users.created_at', 'users.email_verified_at')
        ->get();
    }
}
