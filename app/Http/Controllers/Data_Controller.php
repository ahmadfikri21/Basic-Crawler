<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Main_Model;

class Data_Controller extends Controller
{
    public function __construct(){
        // inisialisasi model
        $this->Main_Model = new Main_Model();
    }

    public function Main(){
        // Main Function
        // list data lpse yang akan diambil
        $data["situsLpse"] = $this->Main_Model->getActiveLpseSite();
        // mengambil seluruh data lpse sesuai dengan url lpse
        $dataLpse = $this->getNLpse($data['situsLpse']);

        // membuat file txt array berdasarkan variabel data lpse
        $arr = array_values($dataLpse);
        // shuffle($arr);
        file_put_contents(base_path().'/app/Http/Controllers/dataLpse.txt', '<?php return '.var_export($arr, TRUE).' ?> ');

        return true;
    }

    // mengambil data lpse dari sejumlah url
    public function getNLpse($situsLpse){
        // jumlah dari lpse yang akan diambil
        $n = count($situsLpse);
        // array untuk menyimpan isi dari tiap lpse, yang dipanggil dengan fungsi getLpseData
        $hasil = [];
        foreach($situsLpse as $url){
            $lpseData = $this->getLpseData($url);
            if($lpseData != false){
                array_push($hasil,$lpseData);
            }
        }

        // mengkonfersi hasil dari 2d menjadi 1d
        $hasil = $this->mergeArray($hasil);
        return $hasil;
    }

    // digunakan untuk mengkonfersi array 2d menjadi 1d
    public function mergeArray($array){
        $newArr = [];
        foreach($array as $d1){
            foreach ($d1 as $d2) {
                $newArr[] = $d2;
            }
        }
        return $newArr;
    }

    // mengambil data lpse dari sebuah url
    public function getLpseData($site){
        $namaSitus = $site->nama_situs;
        $url = $site->link;
        // mengambil halaman lpse
        $lpse = $this->curlGetContents($url);
        // untuk cek apakah situs merespon atau tidak
        if(!$lpse){
            return false;
        }else{
            // mengambil judul dari proyek
            preg_match_all('~<a.*?href="/eproc4/lelang/(.*?)".*?>(.*?)</a>~',$lpse,$judul);
            if(count($judul[0])){
                // mengambil hps dari proyek
                preg_match_all('~<td.*?class="table-hps".*?>(.*?)</td>~',$lpse,$hps);
                // mengambil akhir pendaftaran dari proyek
                preg_match_all('~<td.*?class="center".*?>(.*?)</td>~',$lpse,$deadline);
                // mengambil jenis dari proyek
                preg_match_all('~<td.*?class="bs-callout-info".*?>(.*?)</td>~',$lpse,$count);
                // // mengambil title dari situs lpse
                // preg_match('/\<title.*\>(.*)\<\/title\>/isU',$lpse,$situs);
                
                // // menghilangkan tulisan :Home dari nama situs yang diambil
                // $situs = str_replace(array(":","-","Home","\n"),"",$situs[1]);

                // untuk menghilangkan data non-tender
                $hps[1] = array_slice($hps[1],0,count($judul[2]));
                $deadline[1] = array_slice($deadline[1],0,count($judul[2]));
                $hasil = [];
                
                
                // pengelompokkan jenis sesuai dengan jumlah data dari jenis tersebut di url asli
                // pengadaan barang
                $count1 = $this->filterInt($count[1][0]);
                // jasaKonsultasiBadanUsaha
                $count2 = $this->filterInt($count[1][1]);
                // pekerjaan konstruksi
                $count3 = $this->filterInt($count[1][2]);
                // Jasa Lainnya
                $count4 = $this->filterInt($count[1][3]);
                // Jasa konsultasi perorangan
                $count5 = $this->filterInt($count[1][4]);
                // Jasa Konsultansi Badan Usaha Konstruksi (ada lpse yang tidak ada kategori ini)
                $count6 = (count($count[1]) <= 5) ? null : $this->filterInt($count[1][5]);

                $jenis = "";

                // menyatukan data judul, hps dan deadline yang telah didapat menggunkana array multidimensi
                for ($i=0; $i < count($judul[2]) ; $i++) { 
                    // mengambil lokasi dari setiap pengadaan
                    $return = $this->getLokasiAndTahapPengadaan($url."/lelang/".$judul[1][$i]);
                    
                    if($deadline[1][$i] == " "){
                        $deadline[1][$i] = "Tidak Dicantumkan";
                    }

                    // mengklasifikasikan jenis sesuai dengan jumlah yang ada diatas
                    if($count1 > 0){
                        $jenis = "Pengadaan Barang";
                        $count1--;   
                    }else if($count2 > 0){
                        $jenis = "Jasa Konsultansi Badan Usaha";
                        $count2--;   
                    }else if($count3 > 0){
                        $jenis = "Pekerjaan Konstruksi";
                        $count3--;   
                    }else if($count4 > 0){
                        $jenis = "Jasa Lainnya";
                        $count4--;   
                    }else if($count5 > 0){
                        $jenis = "Jasa Konsultansi Perorangan";
                        $count5--;   
                    }else if($count6 >0){
                        $jenis = "Jasa Konsultansi Badan Usaha Konstruksi";
                        $count6--;   
                    }

                    array_push($hasil,[]);
                    array_push($hasil[$i],$judul[1][$i]);
                    array_push($hasil[$i],$judul[2][$i]);
                    array_push($hasil[$i],$this->changeHpsFormat($hps[1][$i]));
                    array_push($hasil[$i],$deadline[1][$i]);
                    array_push($hasil[$i],$return["lokasi"]);
                    array_push($hasil[$i],$url);
                    array_push($hasil[$i],$jenis);
                    array_push($hasil[$i],$return["tahap"]);
                    array_push($hasil[$i],$namaSitus);

                }
                return $hasil;
            }else{
                return false;
            }
            
        }
    }

    // mengambil angka integer dari string
    public function filterInt($string){
        $string = filter_var($string,FILTER_SANITIZE_NUMBER_INT);
        $string = str_replace("-","",$string);
        return $string;
    }

    // mengambil lokasi dari pengadaan
    public function getLokasiAndTahapPengadaan($url){
        $pengadaan = $this->curlGetContents($url);

        // mengambil lokasi pengadaan
        preg_match_all('~<li>(.*?)</li>~',$pengadaan,$lokasi);
        // mengambil tahap proyek
        preg_match_all('~<a.*?href="/eproc4/lelang/(.*?)/jadwal".*?>(.*?)</a>~',$pengadaan,$tahap);

        if(count($lokasi[1])){
            $lokasi = $lokasi[1][0];
        }else{
            $lokasi = "Lokasi Tidak Dicantumkan";
        }

        if(count($tahap[2])){
            $tahap = str_replace("[...]","",$tahap[2][0]);
        }else{
            $tahap = "Tahap Tidak Dicantumkan";
        }

        $data = [
            "lokasi" => $lokasi,
            "tahap" => $tahap
        ];

        return $data;
    }

    // mengambil data dengan menggunakan curl
    public function curlGetContents($url){
        $ch =  curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    // Untuk merubah format uang dari HPS
    public function changeHpsFormat($hps){
        // menyimpan nilai hps yang telah diambil dari website lpse
        $hpsOriginal = $hps;
        // menghilangkan ,00 dibelakang nominal hps
        $hps = substr($hps, 0, strpos($hps, ","));
        // menghilangkan string dari data hps, sehingga data hps hanya akan berisi angka
        $hps = (int) filter_var($hps, FILTER_SANITIZE_NUMBER_INT);
        if($hps >= 1000000000){
            // dilakukan pembagian dengan 1 miliar jika nilai hps lebih dari 1 miliar
            $hps = $hps/1000000000;
            // data baru dibulatkan 2 angka setelah koma dan disimpan
            $hps = round($hps,2);
        }else if($hps > 100000000){
            // dilakukan pembagian dengan 1 juta jika nilai hps lebih dari 1 juta
            $hps = $hps/1000000;
            // data baru dibulatkan 2 angka setelah koma dan disimpan
            $hps = round($hps,2);
        }else{
            // melakukan filter untuk menghilangkan string
            $hps = (int) filter_var($hpsOriginal, FILTER_SANITIZE_NUMBER_INT);

            if($hps <= 10){
                $hps = $hps;
            }else if($hps>1000 || $hps < 100){
                // jika nilai yang telah difilter > 1000 atau < 100 maka dibagi 10 (agar penempatan koma sesuai dengan situs lpse)
                $hps = $hps/10;
            }
        }
        return $hps;
    }
    
}
