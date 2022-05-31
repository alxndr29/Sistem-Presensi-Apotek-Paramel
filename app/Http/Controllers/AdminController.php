<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    //
    public function index(){
        return view('admin.index');
    }
    public function periodePresensi(){

    }
    public function storePeriodePresensi(Request $request){

    }
    public function pegawai(){
        return view('admin.pegawai');
    }
    public function storePegawai(Request $request){

    }
    public function editPegawai($id){

    }
    public function updatePegawai(Request $request, $id){

    }
    public function destroyPegawai(Request $request){

    }
}
