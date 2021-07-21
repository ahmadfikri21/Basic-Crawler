<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main_Controller;
use App\Http\Controllers\Data_Controller;

Route::get('/getData',[Data_Controller::class,'Main']);
Route::get('/Mainpage',[Main_Controller::class,'index']);
Route::get('/login',[Main_Controller::class,'login']);
Route::get('/Mainpage/logout',[Main_Controller::class,'logout']);

Route::post('/loginProcess',[Main_Controller::class,'loginProcess']);
