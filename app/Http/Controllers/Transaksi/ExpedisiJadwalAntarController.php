<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExpedisiJadwalAntarRequest;
use App\Models\ExpedisiJadwalAntar;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;

class ExpedisiJadwalAntarController extends Controller {

    protected $model, $role;

    public function __construct(ExpedisiJadwalAntar $ExpedisiJadwalAntar) {
        $this->model = new BaseRepository($ExpedisiJadwalAntar);
        $this->middleware('auth');
    }

    public function index() {
        $kurir = DB::table('users')
        ->select('users.*')
        ->where('role_id', '=', 6)
        ->get();

        return view('transaksi.expedisi-jadwal-antar.index',['kurir'=>$kurir]);
    }

    public function getData() {
        //belum difilter untuk orang yg menantar
        $data = DB::table('transaksis')
        ->select('transaksis.*', 'users.name', 'users_deliver.name as deliver_name')
        ->join('members', 'members.id', '=', 'transaksis.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('users as users_deliver', 'users_deliver.id', '=', 'transaksis.deliver_by', 'left')
        ->whereNotNull('transaksis.qc_id')
        ->whereNotNull('transaksis.cuci_id')
        ->whereNotNull('transaksis.pengeringan_id')
        ->whereNotNull('transaksis.setrika_id')
        ->whereNull('transaksis.deleted_at') 
        ->whereNull('transaksis.deliver_at') 
        ->orderBy('transaksis.id', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                // 'url_edit' => route('expedisi-jadwal-antar.edit', $data->id),
                // 'url_detail' => route('expedisi-jadwal-antar.detail', $data->id)
                'url_batal' => route('expedisi-jadwal-antar.destroy', $data->id),
                'url_pilih_kurir'=> $data->id,
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    // public function create() {
    //     try {
    //         $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
    //         return view('transaksi.expedisi-antar.form', compact('roles'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function store(Request $request)
    {  
        
        $transaksi = DB::update('update transaksis set deliver_by = '.$request->deliver_by. ' WHERE transaksis.id ='.$request->transaksi_id);
                         
        return Response()->json($transaksi);
 
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('transaksis')
            ->select('transaksis.*', 'users.name', 'users_deliver.name as deliver_name')
            ->join('members', 'members.id', '=', 'transaksis.member_id', 'left')
            ->join('users', 'users.id', '=', 'members.user_id', 'left')
            ->join('users as users_deliver', 'users_deliver.id', '=', 'transaksis.deliver_by', 'left')
            ->where('transaksis.id',$id)
            ->get();

            return view('transaksi.expedisi-jadwal-antar.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jadwal-antar');
        }
    }

    // public function detail($id) {
    //     try {
    //         $data['detail'] = $this->model->find($id);

    //         return view('transaksi.expedisi-antar.detail', compact('data'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function update(ExpedisiJadwalAntarRequest $request) {
        try {
            // $data = $request->except(['_token', '_method', 'id', 'nama', 'nominal_old']);
            // $user = $this->model->update($request->id, $data);
            
            // $selisih = $request->nominal-$request->nominal_old;
            // echo($selisih);
            // exit();

            DB::update('update transaksis set deliver_by = '.$request->deliver_by. ' WHERE transaksis.id ='.$request->id);

            Alert::toast('Berhasil Disimpan', 'success');
            return redirect()->route('expedisi-jadwal-antart');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jadwal-antarx');
        }
    }

    public function destroy($id) {
        try {

            DB::update("update transaksis set deliver_by = null WHERE transaksis.id=".$id);
            
            Alert::toast('Jadwal Pengantaran Berhasil Dibatalkan', 'success');
            return redirect()->route('expedisi-jadwal-antar');
        } catch (\Throwable $e) {
            echo 'gagal';
            exit();
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jadwal-antar');
        }
    }

    public function getDataUser() {
        $data = DB::table('users')
        ->select('users.*')
        ->where('name', '!=', 'Maintener')
        ->where('role_id', '=', 6)
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function getDataInfo(Request $request) {

        $info  = DB::table('transaksis')->select('transaksis.*', 'users.name', 'users_deliver.name as deliver_name')
        ->join('members', 'members.id', '=', 'transaksis.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('users as users_deliver', 'users_deliver.id', '=', 'transaksis.deliver_by', 'left')
        ->whereNull('transaksis.deleted_at') 
        ->where('transaksis.id',$request->id)
        ->orderBy('transaksis.id', 'ASC')
        ->first();
      
        return Response()->json($info);
        
    }
}
