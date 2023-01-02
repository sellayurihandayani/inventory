<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBarangKeluar extends Model
{
    protected $table            = 'barangkeluar';
    protected $primaryKey       = 'faktur';
    protected $allowedFields    = [
        'faktur', 'tglfaktur', 'idpel', 'totalharga', 'jumlahuang', 'sisauang'
    ];

    public function nofaktur($tanggalSekarang){
        return $this->table('barangkeluar')->select('max(faktur) as nofaktur')->where('tglfaktur', $tanggalSekarang)->get();
    }

    public function laporanPerPeriode($tglawal, $tglakhir){
        return $this->table('barangkeluar')->where('tglfaktur >=', $tglawal)->where('tglfaktur <=', $tglakhir)->get();

    }
}
