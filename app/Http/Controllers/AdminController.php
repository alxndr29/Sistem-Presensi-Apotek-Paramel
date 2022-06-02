<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function index()
    {
        return view('admin.index');
    }
    public function periodePresensi()
    {
        return view('admin.periode');
    }
    public function storePeriodePresensi(Request $request)
    {
        return $request->all();
    }
    public function pegawai()
    {
        $data_pegawai = DB::table('users')->get();
        return view('admin.pegawai', compact('data_pegawai'));
    }
    public function storePegawai(Request $request)
    {
        try {
            DB::table('users')->insert([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => $request->get('role'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return redirect('/pegawai')->with('sukses', 'Berhasil Menambah Data Pegawai');
        } catch (\Exception $e) {
            return redirect('/pegawai')->with('gagal', $e->getMessage());
        }
    }
    public function updatePegawai(Request $request, $id)
    {
        try {
            if ($request->get('password') != "") {
                DB::table('users')->where('id', $id)->update(
                    [
                        'name' => $request->get('name'),
                        'email' => $request->get('email'),
                        'password' => Hash::make($request->get('password')),
                        'role' => $request->get('role'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            } else {
                DB::table('users')->where('id', $id)->update(
                    [
                        'name' => $request->get('name'),
                        'email' => $request->get('email'),
                        // 'password' => Hash::make($request->get('password')),
                        'role' => $request->get('role'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }

            return redirect('/pegawai')->with('sukses', 'Berhasil Ubah Data Pegawai');
        } catch (\Exception $e) {
            return redirect('/pegawai')->with('gagal', $e->getMessage());
        }
    }
    public function destroyPegawai($id)
    {
        try {
            DB::table('users')->where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            return redirect('/pegawai')->with('sukses', 'Berhasil Menghapus Data Pegawai');
        } catch (\Exception $e) {
            return redirect('/pegawai')->with('gagal', $e->getMessage());
        }
    }
}
