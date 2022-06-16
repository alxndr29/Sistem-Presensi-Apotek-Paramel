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
        $periode_presensi = DB::table('periode')->orderBy('aktif', 'desc')->get();
        return view('admin.periode', compact('periode_presensi'));
    }
    public function detailPeriodePresensi($id)
    {
        return view('admin.periodedetail');
    }
    public function storePeriodePresensi(Request $request)
    {
        if ($request->get('jam_akhir') <= $request->get('jam_mulai')) {
            return redirect('/periode')->with('gagal', 'Jam akhir tidak boleh lebih kecil atau sama dengan jam mulai');
        }
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
    public function updateDataPresensi(Request $request, $iduser, $idperiode)
    {
        try {
            DB::table('presensi')
                ->where('users_id', $iduser)
                ->where('periode_idperiode', $idperiode)
                ->update([
                    'jam_absen_masuk' => $request->get('jam_mulai'),
                    'jam_absen_keluar' => $request->get('jam_akhir'),
                    'status' => $request->get('status_kehadiran')
                ]);
            return redirect('/laporan')->with('sukses', 'Berhasil Ubah Data Presensi');
        } catch (\Exception $e) {
            return redirect('/laporan')->with('gagal', 'Berhasil Ubah Data Presensi');
        }
    }
    public function editDataPresensi($iduser, $idperiode)
    {
        $data = DB::table('users')
            ->join('presensi', 'users.id', '=', 'presensi.users_id')
            ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
            //->where('periode.aktif', 1)
            ->where('users.role', 'pegawai')
            ->where("users.id", $iduser)
            ->where("periode.idperiode", $idperiode)
            ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
            ->first();
        return response()->json($data);
    }
    public function pegawai()
    {
        $data_pegawai = DB::table('users')->get();
        // return $data_pegawai;
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
    public function resetNotifikasiTidakHadir($id)
    {
        try {
            DB::table('presensi')->where('users_id', $id)->update([
                'notif' => 1
            ]);
            return redirect('/laporan')->with('sukses', 'Berhasil Reset Notifikasi');
        } catch (\Exception $e) {
            return redirect('/laporan')->with('gagal', $e->getMessage());
        }
    }
    public function laporan($start = null, $end = null)
    {
       
        if ($start == null && $end == null) {
            // return 'masuk sini';
            $data = DB::table('users')
                ->join('presensi', 'users.id', '=', 'presensi.users_id')
                ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
                ->where('periode.aktif', 1)
                ->where('users.role', 'pegawai')
                ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
                ->get();
        } else {
            $data = DB::table('users')
                ->join('presensi', 'users.id', '=', 'presensi.users_id')
                ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
                // ->where('periode.aktif', 1)
                ->where('periode.jam_mulai', '>=', $start)
                ->where('periode.jam_akhir', '<=', $end)
                ->where('users.role', 'pegawai')
                ->orderBy('periode.jam_mulai','desc')
                ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
                ->get();
        }
        //return $data;
        $ketidakhadiran = DB::table('presensi')
            ->join('users', 'users.id', '=', 'presensi.users_id')
            ->select('users.id as userid', 'users.name as username', DB::raw('count(*) as totaltidakhadir'))
            ->where('presensi.status', 'Tidak Hadir')
            ->where('presensi.notif', 0)
            ->groupBy('presensi.users_id')
            ->get();
        $periode = DB::table('periode')->where('aktif', 1)->first();
        return view('admin.laporan', compact('data', 'periode', 'ketidakhadiran','start','end'));
    }
    public function laporanDetailKariawan($id, $start = null, $end = null)
    {
        // return $id;
        $totalbolos = 0;
        $totallibur = 0;
        $totalsakit = 0;
        $totalhadir = 0;
        $totaljamnormal = 0;
        $totaljamaktual = 0;
        $data = DB::table('users')
            ->join('presensi', 'users.id', '=', 'presensi.users_id')
            ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
            ->where('users.id', $id)
            //->where('users.role','Pegawai')
            ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
            ->get();

        // return $data;
        if ($start == null && $end == null) {
            $data = DB::table('users')
                ->join('presensi', 'users.id', '=', 'presensi.users_id')
                ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
                ->where('users.id', $id)
                ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
                ->get();
        } else {
            $data = DB::table('users')
                ->join('presensi', 'users.id', '=', 'presensi.users_id')
                ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
                ->where('users.id', $id)
                ->where('periode.jam_mulai', '>=', $start)
                ->where('periode.jam_akhir', '<=', $end)
                ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
                ->get();
        }
        // return $data;
        foreach ($data as $key => $value) {
            $totaljamnormal += $value->totaljamnormal;
            $totaljamaktual += $value->totalaktual;
            if ($value->status == "Tidak Hadir") {
                $totalbolos++;
            } else if ($value->status == "Hadir") {
                $totalhadir++;
            } else if ($value->status == "Sakit") {
                $totalsakit++;
            } else if ($value->status == "Libur") {
                $totallibur++;
            }
        }
        //return $data;
        return view('admin.laporanperpegawai', compact('data', 'start', 'end', 'totalbolos', 'totallibur', 'totalsakit', 'totalhadir', 'totaljamnormal', 'totaljamaktual', 'id'));
    }
}
