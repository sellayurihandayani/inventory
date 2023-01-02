<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\ModelBarangKeluar;
use App\Models\ModelDataBarang;
use App\Models\ModelDataBarangKeluar;
use App\Models\ModelDetailBarangKeluar;
use App\Models\ModelPelanggan;
use App\Models\ModelTempBarangKeluar;
use Config\Services;

class Barangkeluar extends BaseController
{
    private function buatFaktur(){
        $tanggalSekarang = date('Y-m-d');
        $modelBarangKeluar = new ModelBarangKeluar();

        $hasil = $modelBarangKeluar->noFaktur($tanggalSekarang)->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = substr("$data", -4);
        $nextNoUrut = intval($lastNoUrut) + 1;
        $noFaktur = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        return $noFaktur;
    }
    public function buatNoFaktur(){
        $tanggalSekarang = $this->request->getPost('tanggal');
        $modelBarangKeluar = new ModelBarangKeluar();

        $hasil = $modelBarangKeluar->noFaktur($tanggalSekarang)->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = substr("$data", -4);
        $nextNoUrut = intval($lastNoUrut) + 1;
        $noFaktur = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        
        $json = [
            'nofaktur' => $noFaktur
        ];
        echo json_encode($json);
    }
    public function data()
    {
        return view('barangkeluar/viewdata');
    }

    public function input(){
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('barangkeluar/forminput', $data);
    }

    public function tampilDataTemp(){
        if($this->request->isAJAX()){
            $nofaktur = $this->request->getPost('nofaktur');

            $modalTempBarangKeluar = new ModelTempBarangKeluar();
            $dataTemp = $modalTempBarangKeluar->tampilDataTemp($nofaktur);
            $data = [
                'tampildata' => $dataTemp
            ];

            $json = [
                'data' => view('barangkeluar/datatemp',$data)
            ];
            echo json_encode($json);
        }
    }

    function ambilDataBarang(){
        if($this->request->isAJAX()){
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new Modelbarang();

            $cekData = $modelBarang->find($kodebarang);
            if($cekData == null){
                $json = [
                    'error' => 'Maaf data barang tidak ditemukan'
                ];
            }else{
                $data = [
                    'namabarang' => $cekData['brgnama'],
                    'hargajual' => $cekData['brgharga']
                ];

                $json = [
                    'sukses' => $data
                ];
            };

            $json = [
                'sukses' => $data
            ];
            echo json_encode($json);
        }
    }

    function simpanItem(){
        if($this->request->isAJAX()){
            $nofaktur = $this->request->getPost('nofaktur');
            $kodebarang = $this->request->getPost('kodebarang');
            $namabarang = $this->request->getPost('namabarang');
            $jml = $this->request->getPost('jml');
            $hargajual = $this->request->getPost('hargajual');

            $modelTempBarangKeluar = new ModelTempBarangKeluar();
            $modelBarang = new Modelbarang();

            $ambilDataBarang = $modelBarang->find($kodebarang);
            $stokBarang = $ambilDataBarang['brgstok'];

            if($jml > intval($stokBarang)){
                $json = [
                    'error' => 'Stok tidak mencukupi'
                ];
            }else{
                $modelTempBarangKeluar->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'dethargajual' => $hargajual,
                    'detjumlah' => $jml,
                    'detsubtotal' => intval($jml) * intval($hargajual)
                ]);
    
                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }
            echo json_encode($json);
        }
    }

    function hapusItem(){
        if($this->request->isAJAX()){
            $id = $this->request->getPost('id');

            $modelTempBarangKeluar = new ModelTempBarangKeluar();
            $modelTempBarangKeluar->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    public function modalCariBarang(){
        if($this->request->isAJAX()){
            $json = [
                'data' => view('barangkeluar/modalcaribarang')
            ];
            echo json_encode($json);
        }
    }

    function listDataBarang(){
        $request = Services::request();
        $datamodel = new ModelDataBarang($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-primary\" style=\"text-align: center\" onclick=\"pilih('" . $list->brgkode ."')\">Pilih</button>";

                $row[] = $no;
                $row[] = $list->brgkode;
                $row[] = $list->brgnama;
                $row[] = number_format($list->brgharga,0, ",", ".");
                $row[] = number_format($list->brgstok,0, ",", ".");
                $row[] = $tombolPilih;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    function modalPembayaran(){
        $nofaktur = $this->request->getPost('nofaktur');
        $tglfaktur = $this->request->getPost('tglfaktur');
        $idpelanggan = $this->request->getPost('idpelanggan');
        $totalharga = $this->request->getPost('totalharga');

        $modelTemp = new ModelTempBarangKeluar();
        $cekdata = $modelTemp->tampilDataTemp($nofaktur);

        if($cekdata->getNumRows() > 0){
            $data = [
                'nofaktur' => $nofaktur,
                'totalharga' => $totalharga,
                'tglfaktur' => $tglfaktur,
                'idpelanggan' => $idpelanggan
            ];

            $json = [
                'data' => view('barangkeluar/modalpembayaran', $data)
            ];

        }else{
            $json = [
                'error' => 'Maaf item belum ada'
            ];
        }
        echo json_encode($json);
    }

    function simpanPembayaran(){
        if($this->request->isAJAX()){
            $nofaktur = $this->request->getPost('nofaktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $idpelanggan = $this->request->getPost('idpelanggan');
            $totalbayar = str_replace(".", "", $this->request->getPost('totalbayar'));
            $jumlahuang = str_replace(".", "", $this->request->getPost('jumlahuang'));
            $sisauang = str_replace(".", "", $this->request->getPost('sisauang'));

            $modelBarangKeluar = new ModelBarangKeluar();
            //Simpan ke table barang keluar
            $modelBarangKeluar->insert([
                'faktur' => $nofaktur, 
                'tglfaktur' => $tglfaktur,
                'idpel' => $idpelanggan,
                'totalharga' => $totalbayar,
                'jumlahuang' => $jumlahuang,
                'sisauang' => $sisauang
            ]);

            $modelTemp = new ModelTempBarangKeluar();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $nofaktur]);

            $fieldDetail = [];
            foreach($dataTemp->getResultArray() as $row){
                $fieldDetail[] = [
                    'detfaktur' => $row['detfaktur'],
                    'detbrgkode' => $row['detbrgkode'],
                    'dethargajual' => $row['dethargajual'],
                    'detjumlah' => $row['detjumlah'],
                    'detsubtotal' => $row['detsubtotal'],
                ];
            }

            $modelDetail = new ModelDetailBarangKeluar();
            $modelDetail->insertBatch($fieldDetail);

            //Hapus Temp
            $modelTemp->hapusData($nofaktur);

            $json = [
                'sukses' => 'Transaksi berhasil disimpan',
                'cetakfaktur' => site_url('barangkeluar/cetakfaktur/' . $nofaktur)
            ];

            echo json_encode($json);
        }
    }

    public function cetakfaktur($faktur){
        $modelBarangKeluar = new ModelBarangKeluar();
        $modelDetail = new ModelDetailBarangKeluar();
        $modelPelanggan = new ModelPelanggan();

        $cekData = $modelBarangKeluar->find($faktur);
        $dataPelanggan = $modelPelanggan->find($cekData['idpel']);

        $namaPelanggan = ($dataPelanggan != null) ? $dataPelanggan['pelnama'] : '-';

        if($cekData != null){
            $data = [
                'faktur' => $faktur,
                'tanggal' => $cekData['tglfaktur'],
                'namapelanggan' => $namaPelanggan,
                'detailbarang' => $modelDetail->tampilDataTemp($faktur),
                'jumlahuang' => $cekData['jumlahuang'],
                'sisauang' => $cekData['sisauang']
            ];
            return view('barangkeluar/cetakfaktur', $data);
        }else{
            return redirect()->to(site_url('barangkeluar/input'));
        }
    }

    public function listData(){
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $request = Services::request();
        $datamodel = new ModelDataBarangKeluar($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($tglawal, $tglakhir);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                // $tombolCetak = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"cetak('" . $list->faktur . "')\"><i class=\"fa fa-print\"></i></button>";
                $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->faktur ."')\"><i class=\"fa fa-trash-alt\"></i></button>";

                $tombolEdit = "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"edit('" . $list->faktur ."')\"><i class=\"fa fa-edit\"></i></button>";

                $row[] = $no;
                $row[] = $list->faktur;
                $row[] = $list->tglfaktur;
                $row[] = $list->pelnama;
                $row[] = number_format($list->totalharga, 0, ",", ".");
                $row[] = $tombolHapus . " " . $tombolEdit;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    function hapusTransaksi(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');

            $modelBarangKeluar = new ModelBarangKeluar();

            $db = \Config\Database::connect();

            $db->table('detail_barangkeluar')->delete(['detfaktur' => $faktur]);
            $modelBarangKeluar->delete($faktur);

            $json = [
                'sukses' => 'Transaksi Berhasil Dihapus'
            ];
            echo json_encode($json);
        }
    }

    public function edit($faktur){
        $modelBarangKeluar = new ModelBarangKeluar();
        $modelPelanggan = new ModelPelanggan();
        $rowData = $modelBarangKeluar->find($faktur);
        $rowPelanggan = $modelPelanggan->find($rowData['idpel']);

        $data = [
            'nofaktur' => $faktur,
            'tanggal' => $rowData['tglfaktur'],
            'namapelanggan' => $rowPelanggan['pelnama']
        ];

        return view('barangkeluar/formedit', $data);
    }

    function ambilTotalHarga(){
        if($this->request->isAJAX()){
            $nofaktur = $this->request->getPost('nofaktur');

            $modelDetail = new ModelDetailBarangKeluar();

            $totalHarga = $modelDetail->ambilTotalHarga($nofaktur);

            $json = [
                'totalharga' => "Rp." . number_format($totalHarga, 0, ",", ".")
            ];
            echo json_encode($json);
        }
    }

    function tampilDataDetail(){
        if($this->request->isAJAX()){
            $nofaktur = $this->request->getPost('nofaktur');

            $modelDetail = new ModelDetailBarangKeluar();
            $dataTemp = $modelDetail->tampilDataTemp($nofaktur);
            $data = [
                'tampildata' => $dataTemp
            ];

            $json = [
                'data' => view('barangkeluar/datadetail',$data)
            ];
            echo json_encode($json);
        }
    }

    function hapusItemDetail(){
        if($this->request->isAJAX()){
            $id = $this->request->getPost('id');

            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarangKeluar = new ModelBarangKeluar();

            $rowData = $modelDetail->find($id);
            $noFaktur = $rowData['detfaktur'];

            $modelDetail->delete($id);

            $totalHarga = $modelDetail->ambilTotalHarga($noFaktur);

            // Update pada tabel barang keluar
            $modelBarangKeluar->update($noFaktur,[
                'totalharga' => $totalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    function editItem(){
        if($this->request->isAJAX()){
            $iddetail = $this->request->getPost('iddetail');
            $jml = $this->request->getPost('jml');

            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarangKeluar = new ModelBarangKeluar();

            $rowData = $modelDetail->find($iddetail);
            $noFaktur = $rowData['detfaktur'];
            $hargajual = $rowData['dethargajual'];

            //Update pada tabel detail
            $modelDetail->update($iddetail,[
                'detjumlah' => $jml,
                'detsubtotal' => intval($hargajual) * $jml
            ]);

            //ambil total harga
            $totalHarga = $modelDetail->ambilTotalHarga($noFaktur);
            //update barang keluar
            $modelBarangKeluar->update($noFaktur,[
                'totalharga' => $totalHarga
            ]);

            $json = [
                'sukses' => 'Item Berhasil Di Update'
            ];
            echo json_encode($json);
        }
    }

    function simpanItemDetail(){
        if($this->request->isAJAX()){
            $nofaktur = $this->request->getPost('nofaktur');
            $kodebarang = $this->request->getPost('kodebarang');
            $namabarang = $this->request->getPost('namabarang');
            $jml = $this->request->getPost('jml');
            $hargajual = $this->request->getPost('hargajual');

            $modelTempBarangKeluar = new ModelDetailBarangKeluar();
            $modelBarang = new Modelbarang();

            $ambilDataBarang = $modelBarang->find($kodebarang);
            $stokBarang = $ambilDataBarang['brgstok'];

            if($jml > intval($stokBarang)){
                $json = [
                    'error' => 'Stok tidak mencukupi'
                ];
            }else{
                $modelTempBarangKeluar->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'dethargajual' => $hargajual,
                    'detjumlah' => $jml,
                    'detsubtotal' => $jml * $hargajual
                ]);

                $modelBarangKeluar = new ModelBarangKeluar();

                //ambil total harga
                $totalHarga = $modelTempBarangKeluar->ambilTotalHarga($nofaktur);
                //update barang keluar
                $modelBarangKeluar->update($nofaktur,[
                    'totalharga' => $totalHarga
                ]);
    
                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }
            echo json_encode($json);
        }
    }
}
