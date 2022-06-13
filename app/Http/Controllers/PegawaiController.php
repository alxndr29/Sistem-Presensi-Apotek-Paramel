<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    //
    public function index()
    {
        $tombol_absen = true;
        $periode_presensi = DB::table('periode')->where('aktif', 1)->first();
        $newtimestamp = null;
        $batas_absen = null;
        $status_presensi = null;
        if ($periode_presensi != null) {
            $newtimestamp = strtotime($periode_presensi->jam_mulai . '+ 15 minute');
            $batas_absen =  date('Y-m-d H:i:s', $newtimestamp);

            $status_presensi =  DB::table('presensi')
                ->where('periode_idperiode', $periode_presensi->idperiode)
                ->where('users_id', Auth::user()->id)
                ->first();

            if (date('Y-m-d H:i:s') > $batas_absen) {
                $tombol_absen = false;
            }
        }
        return view('pegawai.index', compact('periode_presensi', 'batas_absen', 'tombol_absen', 'status_presensi'));
    }
    public function presensiMasuk()
    {
        $periode_presensi = DB::table('periode')->where('aktif', 1)->first();
        $dateTimeSekarang =  date('Y-m-d H:i:s');
        $newtimestamp = strtotime($periode_presensi->jam_mulai . '+ 15 minute');
        $batas_absen =  date('Y-m-d H:i:s', $newtimestamp);
        try {
            if ($dateTimeSekarang > $batas_absen) {
                return redirect('/pegawai/home')->with('sukses', 'Absen tidak dapat dilakukan karena melebihi batas waktu.');
            } else {
                DB::table('presensi')
                    ->where('periode_idperiode', $periode_presensi->idperiode)
                    ->where('users_id', Auth::user()->id)
                    ->update([
                        'status' => 'Hadir',
                        'jam_absen_masuk' => $dateTimeSekarang
                    ]);
                return redirect('/pegawai/home')->with('sukses', 'Berhasil Melakukan Absensi');
            }
        } catch (\Exception $e) {
            return redirect('/pegawai/home')->with('gagal', $e->getMessage());
        }
    }
    public function presensiKeluar()
    {
        $periode_presensi = DB::table('periode')->where('aktif', 1)->first();
        $dateTimeSekarang =  date('Y-m-d H:i:s');
        $newtimestamp = strtotime($periode_presensi->jam_akhir);
        $batas_absen =  date('Y-m-d H:i:s', $newtimestamp);
        try {
            if ($dateTimeSekarang < $batas_absen) {
                return redirect('/pegawai/home')->with('sukses', 'Absen Keluar Tidak Dapat Dilakukan Karena Belum Saatnya.');
            } else {
                DB::table('presensi')
                    ->where('periode_idperiode', $periode_presensi->idperiode)
                    ->where('users_id', Auth::user()->id)
                    ->where('status', 'Hadir')
                    ->update([
                        'jam_absen_keluar' => $dateTimeSekarang
                    ]);
                return redirect('/pegawai/home')->with('sukses', 'Berhasil Melakukan Absensi Keluar.');
            }
        } catch (\Exception $e) {
            return redirect('/pegawai/home')->with('gagal', $e->getMessage());
        }
    }
    public function laporan($start = null, $end = null)
    {
        $totalbolos = 0;
        $totallibur = 0;
        $totalsakit = 0;
        $totalhadir = 0;
        $totaljamnormal = 0;
        $totaljamaktual = 0;
        $data = DB::table('users')
            ->join('presensi', 'users.id', '=', 'presensi.users_id')
            ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
            ->where('users.id', Auth::user()->id)
            ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
            ->get();
        if ($start == null && $end == null) {
            $data = DB::table('users')
                ->join('presensi', 'users.id', '=', 'presensi.users_id')
                ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
                ->where('users.id', Auth::user()->id)
                ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
                ->get();
         } else {
            
            $data = DB::table('users')
                ->join('presensi', 'users.id', '=', 'presensi.users_id')
                ->join('periode', 'periode.idperiode', '=', 'presensi.periode_idperiode')
                ->where('users.id', Auth::user()->id)
                ->where('periode.jam_mulai','>=', $start)
                ->where('periode.jam_akhir', '<=', $end)
                ->select('users.id as iduser', 'users.name as username', 'periode.*', 'presensi.*', DB::raw('TIMESTAMPDIFF(hour,jam_mulai,jam_akhir) as totaljamnormal'), DB::raw('TIMESTAMPDIFF(hour,jam_absen_masuk,jam_absen_keluar) as totalaktual'))
                ->get();
         }
        // return $data;
        foreach($data as $key => $value){
            $totaljamnormal += $value->totaljamnormal;
            $totaljamaktual += $value->totalaktual;
            if($value->status == "Tidak Hadir"){
                $totalbolos++;
            }else if($value->status == "Hadir"){
                $totalhadir++;
            }else if($value->status == "Sakit"){
                $totalsakit++;
            }else if($value->status == "Libur"){
                $totallibur++;
            }
        }
        return view('pegawai.laporan',compact('data','start','end','totalbolos','totallibur','totalsakit','totalhadir','totaljamnormal', 'totaljamaktual'));
    }
}
