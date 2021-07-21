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
}
