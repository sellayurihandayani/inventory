<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\This;

class ModelTempBarangKeluar extends Model
{
    protected $table            = 'temp_barangkeluar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'detfaktur', 'detbrgkode', 'dethargajual', 'detjumlah', 'detsubtotal'
    ];

    public function tampilDataTemp($nofaktur){
        return $this->table('temp_barangkeluar')->join('barang','detbrgkode=brgkode')->where('detfaktur', $nofaktur)->get();
    }

    public function hapusData($nofaktur){
        $this->table('temp_barangkeluar')->where('detfaktur', $nofaktur);
        return $this->table('temp_barangkeluar')->delete();
    }
}
