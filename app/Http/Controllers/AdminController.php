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
        $periode_presensi = DB::table('periode')->orderBy('aktif','desc')->get();
        return view('admin.periode',compact('periode_presensi'));
    }
    public function detailPeriodePresensi($id){
        return view('admin.periodedetail');
    }
    public function storePeriodePresensi(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('periode')->update(['aktif' => 0]);
            $id_periode = DB::table('periode')->insertGetId(['jam_mulai' => $request->get('jam_mulai'), 'jam_akhir' => $request->get('jam_akhir'), 'aktif' => 1]);
            $users = DB::table('users')->where('role', 'pegawai')->select('id')->get();
            foreach ($users as $key => $value) {
                DB::table('presensi')->insert([
                    'users_id' => $value->id,
                    'periode_idperiode' => $id_periode,
                    'status' => 'Tidak Hadir',
                    'jam_absen_masuk' => null,
                    'jam_absen_keluar' => null
                ]);
            }
            DB::commit();
            return redirect('/periode')->with('sukses', 'Berhasil Menambah Data Periode');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/periode')->with('gagal', $e->getMessage());
        }
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
    public function laporan()
    {
        $data = DB::table('users')
            ->join('presensi', 'users.id', '=', 'presensi.users_id')
            ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
            ->where('periode.aktif', 1)
            ->where('users.role', 'pegawai')
            ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*')
            ->get();
        $periode = DB::table('periode')->where('aktif',1)->first();
        return view('admin.laporan', compact('data','periode'));
    }
}
