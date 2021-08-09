<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tahapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrTahap = [
            ['tahap' => 'Pengumuman Pascakualifikasi'],
            ['tahap' => 'Download Dokumen Pemilihan'],
            ['tahap' => 'Pemberian Penjelasan'],
            ['tahap' => 'Upload Dokumen Penawaran'],
            ['tahap' => 'Pembukaan Dokumen Penawaran'],
            ['tahap' => 'Evaluasi Administrasi, Kualifikasi, Teknis, dan Ha...'],
            ['tahap' => 'Pembuktian Kualifikasi'],
            ['tahap' => 'Penetapan Pemenang'],
            ['tahap' => 'Pengumuman Pemenang'],
            ['tahap' => 'Masa Sanggah'],
            ['tahap' => 'Surat Penunjukan Penyedia Barang/Jasa'],
            ['tahap' => 'Penandatanganan Kontrak']
        ];
        
        DB::table('tahap_proyek')->insert($arrTahap);
    }
}
