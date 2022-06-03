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

        $newtimestamp = strtotime($periode_presensi->jam_mulai . '+ 15 minute');
        $batas_absen =  date('Y-m-d H:i:s', $newtimestamp);

        $status_presensi =  DB::table('presensi')
            ->where('periode_idperiode', $periode_presensi->idperiode)
            ->where('users_id', Auth::user()->id)
            ->first();
        
        if (date('Y-m-d H:i:s') > $batas_absen) {
            $tombol_absen = false;
        }
        // return $tombol_absen;
        return view('pegawai.index', compact('periode_presensi', 'batas_absen', 'tombol_absen','status_presensi'));
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
}
