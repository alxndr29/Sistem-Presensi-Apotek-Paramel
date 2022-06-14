<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    //return view('admin.index');
    return redirect('/home');
});
Route::get('/jamserver', function () {
    return date('Y-m-d H:i');
})->name('waktuserver');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
//Admin
Route::get('/admin', 'AdminController@index')->name('admin.index');
Route::get('/pegawai', 'AdminController@pegawai')->name('admin.pegawai');
Route::post('/pegawai/store', 'AdminController@storePegawai')->name('admin.storepegawai');
Route::delete('/pegawai/destroy/{id}', 'AdminController@destroyPegawai')->name('admin.destroypegawai');
Route::put('/pegawai/update/{id}', 'AdminController@updatePegawai')->name('admin.updatepegawai');
Route::get('/periode', 'AdminController@periodePresensi')->name('admin.periodepresensi');
Route::get('/periode/detail/{id}', 'AdminController@detailPeriodePresensi')->name('admin.periodedetail');
Route::post('/periode/store', 'AdminController@storePeriodePresensi')->name('admin.storeperiode');
Route::get('/laporan', 'AdminController@laporan')->name('admin.laporan');
Route::put('/presensi/update/{iduser}/{idperiode}', 'AdminController@updateDataPresensi')->name('admin.ubahdatapresensi');
Route::get("/presensi/edit/{iduser}/{idperiode}",'AdminController@editDataPresensi')->name('admin.editdatapresensi');
Route::get("/laporan/pegawai/{id}/{start?}/{end?}",'AdminController@laporanDetailKariawan')->name('admin.detailpegawai');
//Pegawai
Route::get('/pegawai/home', 'PegawaiController@index')->name('pegawai.index');
Route::get('/pegawai/presensimasuk', 'PegawaiController@presensiMasuk')->name('pegawai.presensimasuk');
Route::get('/pegawai/presensikeluar', 'PegawaiController@presensiKeluar')->name('pegawai.presensikeluar');
Route::get('/pegawai/laporan/{start?}/{end?}','PegawaiController@laporan')->name('pegawai.laporan');