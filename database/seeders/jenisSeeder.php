<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrTahap = [
            ['nama_jenis' => 'Pengadaan Barang'],
            ['nama_jenis' => 'Jasa Konsultansi Perorangan'],
            ['nama_jenis' => 'Jasa Konsultansi Badan Usaha'],
            ['nama_jenis' => 'Jasa Konsultansi Badan Usaha Konstruksi'],
            ['nama_jenis' => 'Jasa Konsultansi Badan Usaha Non Konstruksi'],
            ['nama_jenis' => 'Pekerjaan Konstruksi'],
            ['nama_jenis' => 'Jasa Lainnya']
        ];
        
        DB::table('jenis_pengadaan')->insert($arrTahap);
    }
}
