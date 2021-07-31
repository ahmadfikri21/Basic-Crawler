<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main_Controller;
use App\Http\Controllers\Manage_Controller;
use App\Http\Controllers\Data_Controller;

Route::get('/getData',[Data_Controller::class,'Main']);
Route::get('/login',[Main_Controller::class,'login']);
Route::post('/loginProcess',[Main_Controller::class,'loginProcess']);

Route::get('/Mainpage',[Main_Controller::class,'index']);
Route::get('/Mainpage/changePerPageMain',[Main_Controller::class,'changePerPageMain']);
Route::get('/Mainpage/logout',[Main_Controller::class,'logout']);


Route::get('/Manage',[Manage_Controller::class,'index']);

Route::get('/Manage/ManageSite',[Manage_Controller::class,'manageSite']);
Route::get('/Manage/ManageSite/changeStatus',[Manage_Controller::class,'changeStatus']);
Route::get('/Manage/ManageSite/changeStatus/{id}',[Manage_Controller::class,'changeStatus']);
Route::get('/Manage/ManageSite/hapusSitus',[Manage_Controller::class,'hapusSitus']);
Route::get('/Manage/ManageSite/hapusSitus/{id}',[Manage_Controller::class,'hapusSitus']);
Route::post('/Manage/ManageSite/tambahSitus',[Manage_Controller::class,'tambahSitus']);
Route::post('/Manage/ManageSite/editSitus',[Manage_Controller::class,'editSitus']);

Route::get('/Manage/ManageTahap',[Manage_Controller::class,'manageTahap']);
Route::post('/Manage/ManageTahap/tambahTahap',[Manage_Controller::class,'tambahTahap']);
Route::post('/Manage/ManageTahap/editTahap',[Manage_Controller::class,'editTahap']);
Route::get('/Manage/ManageTahap/hapusTahap/',[Manage_Controller::class,'hapusTahap']);
Route::get('/Manage/ManageTahap/hapusTahap/{id}',[Manage_Controller::class,'hapusTahap']);

Route::get('/Manage/ManageSite/changePerPageManage',[Manage_Controller::class,'changePerPageManage']);
