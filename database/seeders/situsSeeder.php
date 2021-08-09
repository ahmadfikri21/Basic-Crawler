<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class situsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = include base_path().'/public/dbData/link_situs_lpse.php';
        DB::table('situs_lpse')->insert($arr);
    }
}
