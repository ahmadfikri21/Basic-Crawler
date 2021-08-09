<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed untuk table users
        DB::table('user')->insert([
            'username' => 'admin',
            'password' => '3bb32493e8b73203c8d4707f32d275fd52d081df217027ab7737d62960c04619'
        ]);

    }
}
