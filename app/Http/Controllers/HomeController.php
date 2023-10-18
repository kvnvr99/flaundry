<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        
        if(Auth::user()->is_member==1){

            $member_info        = DB::table('members')->where('user_id', Auth::user()->id)->first();
            
            $transaksi_terakhir = DB::table('transaksis')->select('transaksis.*')
                                    ->where('transaksis.member_id', $member_info->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();

            $history_transaksi  = DB::table('transaksis')->select('transaksis.*')
                                    ->where('transaksis.member_id', $member_info->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
            

            $data['saldo']              = $member_info->balance;
            $data['history_transaksi']  = $history_transaksi;
            $data['transaksi_terakhir'] = $transaksi_terakhir;

            return view('home_user', compact('data'));    
        }else{
            return view('home');
        }
        
    }

    public function infogram() {

        $today = date('Y-m-d');

        $total  = DB::table('transaksis')->select(DB::raw("ifnull(SUM(kg_cuci),0) as total_kiloan, 
                                                            ifnull(SUM(quantity_qc),0) as total_satuan, 
                                                            ifnull(SUM(bayar),0) as harga_bayar, ifnull(SUM(total),0) as harga_cuci"))
                                    
                            ->whereRaw("left(created_at,10) = '$today'")
                                    // ->orderBy('created_at', 'desc')
                            ->first();

        $nota_masuk = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(created_at,10) = '$today'")->count();

        $nota_keluar = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(deliver_at,10) = '$today'")->count();

        //pekerjaan
        $total_regis = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(created_at,10) = '$today'")
                            ->whereRaw("status in ('registrasi','qc')")
                            ->count();

        $total_cuci = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(created_at,10) = '$today'")
                            ->whereRaw("status ='cuci'")
                            ->count();

        $total_kering = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(created_at,10) = '$today'")
                            ->whereRaw("status ='pengeringan'")
                            ->count();

        $total_setrika = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(created_at,10) = '$today'")
                            ->whereRaw("status ='setrika'")
                            ->count();

        $total_antar = DB::table('transaksis')->select('transaksis.id')
                            ->whereRaw("left(created_at,10) = '$today'")
                            ->whereRaw("deliver_by is not null")
                            ->count();

        $total_jemput = DB::table('permintaan_laundries')->select('permintaan_laundries.id')
                            ->whereRaw("left(created_at,10) = '$today'")
                            ->whereRaw("picked_by is not null")
                            ->count();
            

        $data['total']      = $total;
        $data['nota_masuk'] = $nota_masuk;
        $data['nota_keluar'] = $nota_keluar;
        $data['total_regis'] = $total_regis;
        $data['total_cuci'] = $total_cuci;
        $data['total_kering'] = $total_kering;
        $data['total_setrika'] = $total_setrika;
        $data['total_antar'] = $total_antar;
        $data['total_jemput'] = $total_jemput;

        return view('infogram.infogram', compact('data'));
    }

    public function laporan() {
        return view('laporan.index');
    }

    public function like($id) {

        $like =  DB::update("update transaksis set kepuasan_pelanggan = 'ya' where transaksis.id= " .$id);
                         
        return view('home');
    }

    public function dislike($id) {

        $like =  DB::update("update transaksis set kepuasan_pelanggan = 'netral' where transaksis.id= " .$id);
                         
        return view('home');
    }

    public function netral($id) {
        try {

            DB::update("update transaksis set kepuasan_pelanggan = 'netral' where transaksis.id= " .$id);

            // Alert::toast('Top Up '.$request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('history-laundry');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function infogramExpedisi() {

        return view('infogram.expedisi');

    }

    public function infogramOutlet() {

        return view('infogram.outlet');

    }



}
