<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\HistoryLaundryRequest;
use App\Http\Requests\HistoryLaundryRequestUpdate;
use App\Models\HistoryLaundry;
use App\Models\Corporate;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;
use Auth;

class HistoryLaundryController extends Controller {

    protected $model, $role;

    public function __construct(HistoryLaundry $HistoryLaundry) {
        $this->model = new BaseRepository($HistoryLaundry);
        $this->middleware('auth');
    }

    public function index() {
        return view('corporate.history-laundry.index');
    }

    public function getData() {

        $corporate_id = DB::table('corporate')->where('user_id', Auth::user()->id)->first()->id;

        $data = DB::table('transaksis')
            ->select('transaksis.*', 'parfumes.nama as nama_parfume', 'parfumes.id as parfume_id', 'permintaan_laundries.tanggal', 'permintaan_laundries.waktu')
            ->join('permintaan_laundries', 'permintaan_laundries.id', '=', 'transaksis.permintaan_laundry_id','left')
            ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id','left')
            ->where('transaksis.corporate_id',$corporate_id);
            
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_like' => route('history-laundry-corporate.like', $data->id),
                'url_dislike' => route('history-laundry-corporate.dislike', $data->id),
                'url_catatan' => $data->id
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            
            $data['info'] = DB::table('corporate')
            ->select('members.*')
            ->where('members.user_id', Auth::user()->id)
            ->first();

            return view('corporate.history-laundry.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('history-laundry-corporate');
        }
    }

    public function store(Request $request)
    {  
        
        $transaksi = DB::update("update transaksis set catatan_pelanggan = '".$request->catatan_pelanggan."' WHERE transaksis.id =" .$request->transaksi_id);
                         
        return Response()->json($transaksi);
 
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('permintaan_laundries')
            ->select('permintaan_laundries.*', 'parfumes.nama as nama_parfume', 'parfumes.id as parfume_id', 'layanans.id as layanan_id', 'layanans.nama as nama_layanan')
            ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id','left')
            ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id','left')
            ->where('permintaan_laundries.id',$id)
            ->get();

            return view('corporate.history-laundry.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('history-laundry-corporate');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);

            return view('corporate.history-laundry.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('history-laundry-corporate');
        }
    }

    public function update(HistoryLaundryRequestUpdate $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'nama_parfume', 'nama_layanan']);
            $user = $this->model->update($request->id, $data);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('history-laundry-corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('history-laundry-corporate');
        }
    }

    public function destroy($id) {
        try {
            $this->model->softDelete($id);
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('history-laundry-corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('history-laundry-corporate');
        }
    }

    public function getDataLayanan() {
        $data = DB::table('layanans')
        ->select('layanans.*')
        ->whereNull('layanans.deleted_at')
        ->orderBy('layanans.nama', 'ASC')
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function getDataParfume() {
        $data = DB::table('parfumes')
        ->select('parfumes.*')
        ->whereNull('parfumes.deleted_at')
        ->orderBy('parfumes.nama', 'ASC')
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function like($id) {
        try {

            DB::update("update transaksis set kepuasan_pelanggan = 'ya' where transaksis.id= " .$id);

            // Alert::toast('Top Up '.$request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('history-laundry-corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function dislike($id) {
        try {

            DB::update("update transaksis set kepuasan_pelanggan = 'tidak' where transaksis.id= " .$id);

            // Alert::toast('Top Up '.$request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('history-laundry-corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function netral($id) {
        try {

            DB::update("update transaksis set kepuasan_pelanggan = 'netral' where transaksis.id= " .$id);

            // Alert::toast('Top Up '.$request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('history-laundry-corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function getDataInfo(Request $request) {

        $info  = DB::table('transaksis')->select('transaksis.*')
        ->whereNull('transaksis.deleted_at') 
        ->where('transaksis.id',$request->id)
        ->orderBy('transaksis.id', 'ASC')
        ->first();
      
        return Response()->json($info);
        
    }
}
