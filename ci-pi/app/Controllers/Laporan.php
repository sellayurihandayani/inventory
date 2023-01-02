<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelBarangKeluar;
use App\Models\Modelbarangmasuk;

class Laporan extends BaseController
{
    public function index()
    {
        return view('laporan/index');
    }

    public function cetak_barang_masuk(){
        return view('laporan/viewbarangmasuk');
    }

    public function cetak_barang_masuk_periode(){
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $modelBarangMasuk = new Modelbarangmasuk();

        $dataLaporan = $modelBarangMasuk->laporanPerPeriode($tglawal, $tglakhir);

        $data = [
            'datalaporan' => $dataLaporan,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir
        ];

        return view('laporan/cetakLaporanBarangMasuk', $data);
    }

    public function cetak_barang_keluar(){
        return view('laporan/viewbarangkeluar');
    }

    public function cetak_barang_keluar_periode(){
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $modelBarangKeluar = new ModelBarangKeluar();

        $dataLaporan = $modelBarangKeluar->laporanPerPeriode($tglawal, $tglakhir);

        $data = [
            'datalaporan' => $dataLaporan,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir
        ];

        return view('laporan/cetakLaporanBarangKeluar', $data);
    }
}