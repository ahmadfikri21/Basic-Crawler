<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Main_Model extends Model
{
    public function getUser($username,$password){
        $user = DB::table('user')->where('username',"=",$username)->where('password','=',$password)->get();

        return $user;
    }

    public function getAllCollumnContent($table,$perPage = FALSE){
        if($perPage){
            $content = DB::table($table)->paginate($perPage);
        }else{
            $content = DB::table($table)->get();
        }

        return $content;
    }

    public function getLpseSite($perPage = 20){
        $situs = DB::table('situs_lpse')
                        ->distinct()
                        ->orderByRaw("status ASC, Case kategori
                                        when 'Kementerian' then 1
                                        when 'Instansi' then 2
                                        when 'Provinsi' then 3
                                        when 'Kabupaten' then 4
                                        when 'Kota' then 5
                                    end, nama_situs ASC")
                        ->paginate($perPage);

        return $situs;
    }
    
    public function searchLpseSite($keyword,$perPage = 20){
        $situs = DB::table("situs_lpse")
                        ->where("nama_situs","like","%$keyword%")
                        ->orWhere("kategori","like","%$keyword%")
                        ->orWhere("status","like","%$keyword%")
                        ->orderby("status","ASC")
                        ->paginate($perPage);

        // agar keyword dapat dioper ke url pada saat page ke 2 atau lebih  
        $situs->appends(["keyword" => $keyword]);
        
        return $situs;
    }

    // mengambil situs lpse yang berstatus on
    public function getActiveLpseSite(){
        $situs = DB::table("situs_lpse")
                        ->where("status","=","on")
                        ->orderByRaw("Case kategori
                                        when 'Kementerian' then 1
                                        when 'Instansi' then 2
                                        when 'Provinsi' then 3
                                        when 'Kabupaten' then 4
                                        when 'Kota' then 5
                                    end, nama_situs ASC")
                        ->get();

        return $situs;
    }

    public function changeStatus($idSitus){
        $change = FALSE;
        if($idSitus){
            if(is_array($idSitus)){
                $change = DB::statement("UPDATE situs_lpse SET status = IF(status = 'on', 'off', 'on') WHERE id_situs in (".implode(',',$idSitus).")");
            }else{
                $change = DB::statement("UPDATE situs_lpse SET status = IF(status = 'on', 'off', 'on') WHERE id_situs = $idSitus");
            }
        }

        return $change;
    }

    public function tambahSitus($request){
        $namaSitus = $request->post("namaSitus");
        $link = $request->post("link");
        $kategori = $request->post("kategori");
        $status = $request->post("status");

        $insert = DB::table("situs_lpse")->insert([
            "nama_situs" => $namaSitus,
            "link" => $link,
            "kategori" => $kategori,
            "status" => $status
        ]);

        return $insert;
    }
    
    public function editSitus($request){
        $id = $request->post("idSitus");
        $namaSitus = $request->post("namaSitus");
        $link = $request->post("link");
        $kategori = $request->post("kategori");
        $status = $request->post("status");

        $update = DB::table("situs_lpse")
                        ->where('id_situs',$id)
                        ->update([
                            "nama_situs" => $namaSitus,
                            "link" => $link,
                            "kategori" => $kategori,
                            "status" => $status
                        ]);

        return $update;
    }

    public function hapusSitus($id){
        if(is_array($id)){
            $delete = DB::table("situs_lpse")->wherein("id_situs",$id)->delete();
        }else{
            $delete = DB::table("situs_lpse")->where("id_situs","=",$id)->delete();
        }

        return $delete;
    }

    // ========== Manajemen Tahap =================

    public function searchTahapProyek($keyword,$perPage = 10){
        $tahap = DB::table("tahap_proyek")
                        ->where("tahap","like","%$keyword%")
                        ->paginate($perPage);

        // agar keyword dapat dioper ke url pada saat page ke 2 atau lebih  
        $tahap->appends(["keyword" => $keyword]);
        
        return $tahap;
    }

    public function tambahTahap($request){
        $tahap = $request->post("tahap");

        $insert = DB::table("tahap_proyek")->insert(["tahap" => $tahap]);

        return $insert;
    }

    public function editTahap($request){
        $id = $request->post("idTahap");
        $tahap = $request->post("tahap");

        $update = DB::table("tahap_proyek")
                        ->where('id_tahap',$id)
                        ->update([
                            "tahap" => $tahap
                        ]);

        return $update;
    }

    public function hapusTahap($id){
        if(is_array($id)){
            $delete = DB::table("tahap_proyek")->wherein("id_tahap",$id)->delete();
        }else{
            $delete = DB::table("tahap_proyek")->where("id_tahap","=",$id)->delete();
        }
    
        return $delete;
    }


}
