<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use App\Models\Main_Model;

class Main_Controller extends Controller
{
    public function __construct(){
        // inisialisasi model
        $this->Main_Model = new Main_Model();
    }

    // view login
    public function login(Request $request){
        if($request->session()->get('username')){
            return redirect("/Mainpage");
        }else{
            return view('loginpage');
        }
    }

    // proses login
    public function loginProcess(Request $request){
        // memvalidasi input apakah kosong atau tidak
        $request->validate([
            "username" => "required",
            "password" => "required"
        ]);

        // mengambil isian dari input
        $username = $request->post("username");
        $password = $request->post("password");

        // melakukan hashing untuk password menggunakan sha256
        $hash = hash('sha256',$password);
        // mengambil data user dari database sesuai dengan username dan password yang telah di hash
        $userdata = $this->Main_Model->getUser($username,$hash);

        // kondisi untuk cek apakah username dan password benar
        if(count($userdata)) {
            // memasukkan data user ke session
            $request->session()->put('username',$username);

            // mengarahkan ke halaman mainpage
            return redirect("/Mainpage");
        }else{
            // membuat flash data jika username atau password salah
            $request->session()->flash('notice', 'Username or password is incorrect');
            // mengarahkan ke page login
            return redirect("/login");
        }
    }

    public function index(Request $request){
        // kondisi untuk cek apakah user telah login atau belum
        if($request->session()->get('username')){
            $data["urlLpse"] =  
            [
                "https://lpse.surabaya.go.id/",
                "https://www.lpse.nganjukkab.go.id/",
                "https://lpse.kalteng.go.id/",
                "https://lpse.jogjaprov.go.id/",
                "https://lpse.jabarprov.go.id/",
                "https://lpse.jatimprov.go.id/",
                "http://lpse.sumbarprov.go.id/",
                "https://eproc.denpasarkota.go.id/",
                "https://lpse.gorontaloprov.go.id/",
                "http://www.lpse.pekanbaru.go.id/",
                "https://lpse.bandaacehkota.go.id/",
                "http://lpse.jogjakota.go.id/",
                "http://lpse.kepriprov.go.id/",
                "https://lpse.banjarbarukota.go.id/"
            ];

            // mengambil data lpse dari array external
            $data["lpse"] = include "dataLpse.txt";
            
            // mengambil page keberapa dari pagination
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // jumlah data yang ditampilkan setiap halaman
            $perPage = 10;
            // menentukan dari mana data diambil pada saat melakukan array slice.
            $from = $currentPage*$perPage-$perPage;

            $keyword = (isset($_GET["keyword"]) == "") ? FALSE : $_GET["keyword"];
            $lpseSite = (isset($_GET["lpseSite"]) == "") ? FALSE : $_GET["lpseSite"];
            $filterHps = (isset($_GET["filterHps"]) == "") ? FALSE : $_GET["filterHps"];

            // kondisi untuk menghandle jika dilakukan pencarian
            if ($keyword || $lpseSite || $filterHps){
                // data lpse yang telah difilter sesuai dengan keyword pencarian
                $data["lpse"] = $this->filterLpse($data["lpse"],$_GET['keyword'],$_GET["lpseSite"],$_GET["filterHps"]);
                
                // menghitung jumlah data yang telah difilter
                $total = count($data["lpse"]);
                
                // melakukan pembagian menggunakan fungsi dari laravel
                $data["lpse"] = new LengthAwarePaginator(array_slice($data["lpse"],$from,$perPage), $total, $perPage, $currentPage);
                
                // memodifikasi path url dari pagination
                $data["lpse"]->withPath("/Mainpage?keyword=".$request->get('keyword')."&lpseSite=".$request->get("lpseSite")."&filterHps=".$request->get("filterHps"));
            }else{
                $total = count($data["lpse"]);
                
                $data["lpse"] = new LengthAwarePaginator(array_slice($data["lpse"],$from,$perPage), $total, $perPage, $currentPage);
                
                $data["lpse"]->withPath("/Mainpage");
            }
            
            return view('mainpage',$data);
        }else{
            return redirect("/login");
        }
    }

    public function filterLpse($data,$keyword = FALSE,$lpseSite = FALSE,$filterHps = FALSE){
        // melakukan filtering berdasarkan situs lpse yang diinput
        if($lpseSite){
            $array = [];
            for ($i=0; $i < count($data); $i++) { 
                if ($data[$i][5] == $lpseSite){
                    $array[] = $data[$i];
                }
            }
            $data = $array;
        }

        // melakukan filtering berdasarkan keyword yang diinput
        if($keyword){
            $array2 = [];
            $keyword = strtolower($keyword);
            for ($k=0; $k < count($data); $k++) { 
                if (str_contains(strtolower($data[$k][1]),$keyword)){
                    $array2[] = $data[$k];
                }
            }

            $data = $array2;
        }

        // melakukan filtering berdasarkan filter HPS
        if($filterHps){
            $array3 = [];
            
            for ($j=0; $j < count($data); $j++) { 
                if($filterHps == "1"){
                    $kondisi = ($data[$j][2] >= 100 && $data[$j][2] < 500);
                }else if($filterHps == "2"){
                    $kondisi = ($data[$j][2] >= 500 && $data[$j][2] <= 999);
                }else{
                    $kondisi = ($data[$j][2] < 100);
                }
                
                if ($kondisi){
                    $array3[] = $data[$j];
                }
            }

            $data = $array3;
        }

        return $data;
    }

    public function logout(Request $request){
        $request->session()->flush();
        return redirect("/login");
    }
}
