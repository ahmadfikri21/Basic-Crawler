<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use App\Models\Main_Model;

class Manage_Controller extends Controller
{
    public function __construct(){
        // inisialisasi model
        $this->Main_Model = new Main_Model();
    }

    public function index(Request $request){
        if($request->session()->get('username')){
            $data["title"] = "Manage";

            return view("manageHome",$data);
        }else{
            return redirect("/login");
        }
    }
    
    // manajemen situs
    public function manageSite(Request $request){
        if($request->session()->get('username')){
            // menentukan page nama ini
            $data["title"] = "Manajemen Situs";
            // jumlah pagination per halaman
            $data["perPage"] = ($request->session()->get("perPageManage")) ? $request->session()->get("perPageManage") : 20 ;
            // mengambil data situs lpse
            $data["dataSitus"] = $this->Main_Model->getLpseSite($data["perPage"]);
            $keyword = (isset($_GET["keyword"])) ? $_GET["keyword"] : false;
    
            if($keyword){
                $data["dataSitus"] = $this->Main_Model->searchLpseSite($keyword,$data["perPage"]);
            }
    
            return view("manageSite",$data);
        }else{
            return redirect("/login");
        }
    }

    public function changeStatus(Request $request,$idSitus = FALSE){
        if($request->session()->get('username')){
            if($request->post("checkbox")){
                $idSitus = $request->post("checkbox");
            }

            if($this->Main_Model->changeStatus($idSitus)){
                $request->session()->flash('succNotice','Status berhasil diubah !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalah, status tidak berubah !');
            }

            return redirect()->back();
            
        }else{
            return redirect("/login");
        }
    }

    public function tambahSitus(Request $request){
        if($request->session()->get('username')){
            // memvalidasi input apakah kosong atau tidak
            $request->validate([
                "namaSitus" => "required",
                "link" => "required",
                "status" => "required",
                "kategori" => "required"
            ]);

            if($this->Main_Model->tambahSitus($request)){
                $request->session()->flash('succNotice','Data berhasil ditambahkan !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalahan, Gagal menambahkan data !');
            }

            return redirect()->back();
        }else{
            return redirect("/login");
        }
    }
    
    public function editSitus(Request $request){
        if($request->session()->get('username')){
            // memvalidasi input apakah kosong atau tidak
            $request->validate([
                "namaSitus" => "required",
                "link" => "required",
                "status" => "required",
                "kategori" => "required"
            ]);

            if($this->Main_Model->editSitus($request)){
                $request->session()->flash('succNotice','Data berhasil diedit !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalahan, Gagal mengedit data !');
            }

            return redirect()->back();
        }else{
            return redirect("/login");
        }
    }
    
    public function hapusSitus(Request $request,$id = FALSE){
        if($request->post("checkbox")){
            $id = $request->post("checkbox");
        }

        if($request->session()->get('username')){
            if($this->Main_Model->hapusSitus($id)){
                $request->session()->flash('succNotice','Data berhasil dihapus !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalahan, Gagal menghapus data !');
            }

            return redirect()->back();
        }else{
            return redirect("/login");
        }
    }

    public function changePerPageManage(Request $request){
        $value = $request->get('value');
        $request->session()->put('perPageManage',$value);
    }

    // ============== Manajemen Tahap ===================

    public function manageTahap(Request $request){
        if($request->session()->get('username')){
            // menentukan page nama ini
            $data["title"] = "Manajemen Tahap";
            $data["perPage"] = 20;
            // mengambil data situs lpse
            $data["dataTahap"] = $this->Main_Model->getAllCollumnContent("tahap_proyek",$data["perPage"]);
            $keyword = (isset($_GET["keyword"])) ? $_GET["keyword"] : false;
    
            if($keyword){
                $data["dataTahap"] = $this->Main_Model->searchTahapProyek($keyword,$data["perPage"]);
            }
    
            return view("manageTahap",$data);
        }else{
            return redirect("/login");
        }
    }

    public function tambahTahap(Request $request){
        if($request->session()->get('username')){
            // memvalidasi input apakah kosong atau tidak
            $request->validate([
                "tahap" => "required"
            ]);

            if($this->Main_Model->tambahTahap($request)){
                $request->session()->flash('succNotice','Data berhasil ditambahkan !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalahan, Gagal menambahkan data !');
            }

            return redirect()->back();
        }else{
            return redirect("/login");
        }
    }

    public function editTahap(Request $request){
        if($request->session()->get('username')){
            // memvalidasi input apakah kosong atau tidak
            $request->validate([
                "tahap" => "required"
            ]);

            if($this->Main_Model->editTahap($request)){
                $request->session()->flash('succNotice','Data berhasil diedit !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalahan, Gagal mengedit data !');
            }

            return redirect()->back();
        }else{
            return redirect("/login");
        }
    }

    public function hapusTahap(Request $request,$id = FALSE){
        if($request->session()->get('username')){
            if($request->post("checkbox")){
                $id = $request->post("checkbox");
            }

            if($this->Main_Model->hapusTahap($id)){
                $request->session()->flash('succNotice','Data berhasil dihapus !');
            }else{
                $request->session()->flash('errNotice','Maaf terjadi kesalahan, Gagal menghapus data !');
            }

            return redirect()->back();
        }else{
            return redirect("/login");
        }
    }
}
