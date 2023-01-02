<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modeltempbarangmasuk;
use App\Models\Modelbarangmasuk;
use App\Models\Modeldetailbarangmasuk;

class Barangmasuk extends BaseController
{
    public function index()
    {
        return view('barangmasuk/forminput');
    }

    function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelTemp = new Modeltempbarangmasuk();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/datatemp', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf Data Tidak Bisa Dipanggil');
        }
    }

    function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new Modelbarang();
            $ambilData = $modelBarang->find($kodebarang);

            if ($ambilData == NULL){
                $json = [
                    'error' => 'Data Barang Tidak Ditemukan'
                ];
            }else{
                $data = [
                    'namabarang' => $ambilData['brgnama'],
                    'hargajual' => $ambilData['brgharga']
                ];
    
                $json = [
                    'sukses' => $data
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf Data Tidak Bisa Dipanggil');
        }
    }

    function simpanTemp(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelTempBarang = new Modeltempbarangmasuk();

            $modelTempBarang->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjumlah' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli)
            ]);

            $json = [
                'sukses' => 'Item Berhasil Ditambahkan'
            ];
            echo json_encode($json);
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }
    function hapus(){
        if($this->request->isAJAX()){
            $id = $this->request->getPost('id');

            $modelTempBarang = new Modeltempbarangmasuk();
            $modelTempBarang->delete($id);

            $json = [
                'sukses' => 'Item Berhasil Dihapus'
            ];
            echo json_encode($json);
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }

    function cariDataBarang(){
        if($this->request->isAJAX()){
            $json = [
                'data' => view('barangmasuk/modalcaribarang')
            ];
            echo json_encode($json);
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }

    function detailCariBarang(){
        if($this->request->isAJAX()){
            $cari = $this->request->getPost('cari');

            $modalBarang = new Modelbarang();
            
            $data = $modalBarang->tampildata_cari($cari)->get();

            if($data != null){
                $json = [
                    'data' => view('barangmasuk/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];

                echo json_encode($json);
            }
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }

    function selesaiTransaksi(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');
            $tglfaktur = $this->request->getPost('tglfaktur');

            $modelTemp = new Modeltempbarangmasuk();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $faktur]);

            if($dataTemp->getNumRows() == 0){
                $json = [
                    'error' => 'Maaf, Data Item Untuk Faktur Ini Tidak Tersedia'
                ];
            }else{
                $modelBarangMasuk = new Modelbarangmasuk();
                $totalSubTotal = 0;
                foreach($dataTemp->getResultArray() as $total):
                    $totalSubTotal += intval($total['detsubtotal']);
                endforeach;

                $modelBarangMasuk->insert([
                    'faktur' => $faktur,
                    'tglfaktur' => $tglfaktur,
                    'totalharga' => $totalSubTotal
                ]);

                //Menyimpan ke tabel detailbarangmasuk
                $modelDetailBarangMasuk = new Modeldetailbarangmasuk();
                foreach($dataTemp->getResultArray() as $row):
                    $modelDetailBarangMasuk->insert([
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'dethargamasuk' => $row['dethargamasuk'],
                        'dethargajual' => $row['dethargajual'],
                        'detjumlah' => $row['detjumlah'],
                        'detsubtotal' => $row['detsubtotal'],
                    ]);
                endforeach;

                //Hapus data di tabel temp_detailbarangmasuk
                $modelTemp->emptyTable();

                $json = [
                    'sukses' => 'Selamat, Transaksi Berhasil Disimpan'
                ];
            }
            echo json_encode($json);
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }
    public function data(){
        $tombolcari = $this->request->getPost('tombolcari');

        if(isset($tombolcari)){
            $cari = $this->request->getPost('cari');
            session()->set('cari_faktur', $cari);
            redirect()->to('/barangmasuk/data');
        }else{
            $cari = session()->get('cari_faktur');
        }

        $modelBarangMasuk = new Modelbarangmasuk();

        $totaldata = $cari ? $modelBarangMasuk->tampildata_cari($cari)->countAllResults() : $modelBarangMasuk->countAllResults();

        $databarangmasuk = $cari ? $modelBarangMasuk->tampildata_cari($cari)->paginate(10, 'barangmasuk') : $modelBarangMasuk->paginate(10, 'barangmasuk');

        $nohalaman = $this->request->getVar('page_barangmasuk') ? $this->request->getVar('page_barangmasuk') : 1;

        $data = [
            'tampildata' => $databarangmasuk,
            'pager' => $modelBarangMasuk->pager,
            'nohalaman' => $nohalaman,
            'totaldata' => $totaldata,
            'cari' => $cari
        ];

        return view('barangmasuk/viewdata', $data);
    }

    function detailItem(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();
            $dataDetail = $modelDetail->dataDetail($faktur);

            $data = [
                'tampildatadetail' => $dataDetail
            ];

            $json = [
                'data' => view('barangmasuk/modaldetailitem', $data)
            ];
            echo json_encode($json);
        }
    }

    function edit($faktur){
        $modelBarangMasuk = new Modelbarangmasuk();
        $cekFaktur = $modelBarangMasuk->cekFaktur($faktur);

        if($cekFaktur->getNumRows() > 0){
            $row = $cekFaktur->getRowArray();

            $data = [
                'nofaktur' => $row['faktur'],
                'tanggal' => $row['tglfaktur']
            ];
            return view('barangmasuk/formedit', $data);
        }else{
            exit('Data Tidak Ditemukan');
        }
    }

    function dataDetail(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();

            $data = [
                'datadetail' => $modelDetail->dataDetail($faktur)
            ];

            $totalHargaFaktur = number_format($modelDetail->ambilTotalHarga($faktur),0, ",", ".");
            $json = [
                'data' => view('barangmasuk/datadetail', $data),
                'totalharga' => $totalHargaFaktur
            ];
            echo json_encode($json);
        }
    }

    function editItem(){
        if($this->request->isAJAX()){
            $iddetail = $this->request->getPost('iddetail');

            $modelDetail = new Modeldetailbarangmasuk();
            $ambilData = $modelDetail->ambilDetailBerdasarkanID($iddetail);

            $row = $ambilData->getRowArray();

            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'hargajual' => $row['dethargajual'],
                'hargabeli' => $row['dethargamasuk'],
                'jumlah' => $row['detjumlah']
            ];

            $json = [
                'sukses' => $data
            ];
            echo json_encode($json);
        }
    }

    function simpanDetail(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();

            $modelDetail->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjumlah' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli)
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);

            $modelBarangMasuk->update($faktur,[
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item Berhasil Ditambahkan'
            ];
            echo json_encode($json);
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }

    function updateItem(){
        if($this->request->isAJAX()){
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');
            $iddetail = $this->request->getPost('iddetail');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();

            $modelDetail->update($iddetail, [
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjumlah' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($hargabeli)
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);

            $modelBarangMasuk->update($faktur,[
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item Berhasil Diupdate'
            ];
            echo json_encode($json);
        }
    }

    function hapusItemDetail(){
        if($this->request->isAJAX()){
            $id = $this->request->getPost('id');
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();

            $modelDetail->delete($id);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);

            $modelBarangMasuk->update($faktur,[
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item Berhasil Dihapus'
            ];
            echo json_encode($json);
        }else{
            exit('Maaf Tidak Bisa Dipanggil');
        }
    }

    public function hapusTransaksi(){
        $faktur = $this->request->getPost('faktur');

        $db = \Config\Database::connect();
        $modelBarangMasuk = new Modelbarangmasuk();

        $db->table('detail_barangmasuk')->delete(['detfaktur' => $faktur]);
        $modelBarangMasuk->delete($faktur);

        $json = [
            'sukses' => "Transaksi Dengan Nomor Faktur $faktur Berhasil Dihapus"
        ];
        echo json_encode($json);
    }
}