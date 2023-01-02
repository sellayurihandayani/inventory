<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modeluser;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        if(session()->idlevel == 2){
            return redirect()->to('/login/index');
        }
        else{
            return view('/users/data');   
        }
    }

    function listData()
    {
        if($this->request->isAJAX()){
            $db = \Config\Database::connect();
            $builder = $db->table('users')->select('userid, usernama, levelnama, useraktif, userlevelid')->join('levels', 'levelid = userlevelid');
            return DataTable::of($builder)
            ->addNumbering('nomor')
            ->add('status', function($row){
                if($row->useraktif == '1'){
                    return '<span class="btn btn-sm btn-success">Aktif</span>';
                }else{
                    return '<span class="btn btn-sm btn-danger">Tidak Aktif</span>';
                }
                return '';
            })
            ->add('aksi', function($row){
                if($row->userlevelid != '1'){
                    return "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"view('".$row->userid."')\">
                        View
                    </button>";
                }
            })
            ->toJson(true);
        }
    }

    function formtambah(){
        if($this->request->isAJAX()){
            $db = \Config\Database::connect();

            $data = [
                'datalevel' => $db->table('levels')->where('levelid !=', '1')->get()
            ];
            echo view('users/modaltambah', $data);
        }
    }

    function formedit(){
        if($this->request->isAJAX()){
            $iduser = $this->request->getPost('userid');
            $modelUser = new Modeluser();
            $rowUser = $modelUser->find($iduser);

            if($rowUser){

                $db = \Config\Database::connect();
                $data = [
                    'datalevel' => $db->table('levels')->where('levelid !=', '1')->get(),
                    'iduser' => $iduser,
                    'namalengkap' => $rowUser['usernama'],
                    'level' => $rowUser['userlevelid'],
                    'status' => $rowUser['useraktif']
                ];
                echo view('users/modaledit', $data);
            }
        }
    }

    function simpan(){
        if($this->request->isAJAX()){
            $iduser = $this->request->getVar('iduser');
            $namalengkap = $this->request->getVar('namalengkap');
            $level = $this->request->getVar('level');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'iduser' => [
                    'rules' => 'required|is_unique[users.userid]',
                    'label' => 'ID User',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'is_unique' => '{field} tidak boleh sama'
                    ]
                ],
                'namalengkap' => [
                    'rules' => 'required',
                    'label' => 'Nama Lengkap',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'level' => [
                    'rules' => 'required',
                    'label' => 'Level',
                    'errors' => [
                        'required' => '{field} wajib dipilih',
                    ]
                ],

            ]);
            if(!$valid){
                $error = [
                    'iduser' => $validation->getError('iduser'),
                    'namalengkap' => $validation->getError('namalengkap'),
                    'level' => $validation->getError('level')
                ];

                $json = [
                    'error' => $error
                ];
            }else{
                $modelUser = new Modeluser();
                $modelUser->insert([
                    'userid' => $iduser,
                    'usernama' => $namalengkap,
                    'userlevelid' => $level
                ]);

                $json = [
                    'sukses' => 'Simpan data User berhasil'
                ];
            }
            echo json_encode($json);
        }
    }

    function update(){
        if($this->request->isAJAX()){
            $iduser = $this->request->getVar('iduser');
            $namalengkap = $this->request->getVar('namalengkap');
            $level = $this->request->getVar('level');

            $modelUser = new Modeluser();
            $modelUser->update($iduser, [
                'userid' => $iduser,
                'usernama' => $namalengkap,
                'userlevelid' => $level
            ]);

            $json = [
                'sukses' => 'Update data User berhasil'
            ];
        echo json_encode($json);
        }
    }

    function updateStatus(){
        if($this->request->isAJAX()){
            $iduser = $this->request->getVar('iduser');
            $modelUser = new Modeluser();
            $rowuser = $modelUser->find($iduser);

            $useraktif = $rowuser['useraktif'];

            if($useraktif=='1'){
                $modelUser->update($iduser,[
                    'useraktif' => '0'
                ]);
            }else{
                $modelUser->update($iduser,[
                    'useraktif' => '1'
                ]);
            }
            $json = [
                'sukses' => ''
            ];
            echo json_encode($json);
        }
    }

    function hapus(){
        if($this->request->isAJAX()){
            $iduser = $this->request->getPost('iduser');

            $modelUser = new Modeluser();
            $modelUser->delete($iduser);

            $json = [
                'sukses' => 'ID User berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    function resetPassword(){
        if($this->request->isAJAX()){
            $iduser = $this->request->getPost('iduser');

            $modelUser = new Modeluser();
            $passRandom = rand(1,99999999);

            $passHashBaru = password_hash($passRandom, PASSWORD_DEFAULT);

            $modelUser->update($iduser,[
                'userpassword' => $passHashBaru
            ]);

            $json = [
                'sukses' => '',
                'passwordBaru' => $passRandom
            ];

            echo json_encode($json);
        }
    }
    
}