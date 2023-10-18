<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExpedisiJemputRequest;
use App\Http\Requests\ExpedisiJemputRequestUpdate;
use App\Models\ExpedisiJemput;
use App\Models\ExpedisiJemputImage;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;

class ExpedisiJemputController extends Controller {

    protected $model, $role, $images;

    public function __construct(ExpedisiJemput $expedisijemput, ExpedisiJemputImage $ExpedisiJemputImage) {
        $this->model = new BaseRepository($expedisijemput);
        $this->images = new BaseRepository($ExpedisiJemputImage);
        $this->middleware('auth');
    }

    public function index() {
        return view('transaksi.expedisi-jemput.index');
    }

    public function getData() {
        //belum difilter untuk orang yg menjemput
        $data = DB::table('permintaan_laundries')
        ->select("permintaan_laundries.*", "users.name",DB::raw("(case when permintaan_laundries.id IN (select permintaan_laundry_id from expedisi_jemputs) then 'sudah' else '-' end) as status"))
        ->join('members', 'members.id', '=', 'permintaan_laundries.member_id')
        ->join('users', 'users.id', '=', 'members.user_id')
        ->whereNull('permintaan_laundries.deleted_at')
        ->orderBy('permintaan_laundries.tanggal', 'ASC')
        ->orderBy('permintaan_laundries.waktu', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('expedisi-jemput.edit', $data->id),
                // 'url_detail' => route('expedisi-jemput.detail', $data->id)
                'url_batal' => route('expedisi-jemput.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    // public function create() {
    //     try {
    //         $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
    //         return view('transaksi.expedisi-jemput.form', compact('roles'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function store(ExpedisiJemputRequest $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'nama', 'waktu', 'alamat', 'tanggal', 'image']);
            $waktu = date('Y-m-d H:i:s');
            
            DB::update("update permintaan_laundries set status_jemput = '1', picked_at = '".$waktu."' where permintaan_laundries.id= " .$request->permintaan_laundry_id);

            // DB::update("insert into transaksis
            //             (kode_transaksi, permintaan_laundry_id, member_id, nama, alamat, parfume, created_at)
            //             select concat('PM-',permintaan_laundries.id) kode, permintaan_laundries.id, permintaan_laundries.member_id, users.name, permintaan_laundries.alamat, permintaan_laundries.parfume_id, permintaan_laundries.picked_at from permintaan_laundries
            //             left join members on members.id =permintaan_laundries.member_id
            //             left join users on users.id = members.user_id
            //             WHERE permintaan_laundries.id = '".$request->permintaan_laundry_id."'" );

            if($request->id==''){
                $expedisijemput = $this->model->store($data);
                if($request->hasfile('images')) {
                    $images = [];
                    foreach($request->file('images') as $file) {
                        $image = [];
                        $image['expedisi_jemput_id'] = $expedisijemput->id;
                        $image['image'] = $file->store('transaksi/penjemputan', 'public');
                        $images [] = $this->images->store($image);
                    }
                }
            }else{
                $expedisijemput = $this->model->update($request->id, $data);
            }

            Alert::toast('Data Berhasil Disimpan', 'success');
            return redirect()->route('expedisi-jemput');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jemput');
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('permintaan_laundries')
            ->select('permintaan_laundries.*', 'users.name','expedisi_jemputs.titip_saldo', 'expedisi_jemputs.catatan', 'expedisi_jemputs.id as expedisi_jemput_id', 'expedisi_jemputs.image')
            ->join('members', 'members.id', '=', 'permintaan_laundries.member_id')
            ->join('users', 'users.id', '=', 'members.user_id')
            ->join('expedisi_jemputs', 'expedisi_jemputs.permintaan_laundry_id', '=', 'permintaan_laundries.id','left')
            ->where('permintaan_laundries.id',$id)
            ->get();

            return view('transaksi.expedisi-jemput.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jemput');
        }
    }

    // public function detail($id) {
    //     try {
    //         $data['detail'] = $this->model->find($id);

    //         return view('transaksi.expedisi-jemput.detail', compact('data'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function update(ExpedisiJemputRequest $request) {
        
        try {
            $data = $request->except(['_token', '_method', 'id', 'nama', 'nominal_old']);
            
            if($request->id==''){
                $user = $this->model->create($data);
                DB::update('update permintaan_laundries set status_jemput = 1 where permintaan_laundries.id= ' .$request->permintaan_laundry_id);
            }else{
                $user = $this->model->update($request->id, $data);
            }

            Alert::toast('Data Berhasil Disimpan', 'success');
            return redirect()->route('expedisi-jemput');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jemput');
        }
    }

    public function destroy($id) {
        try {

            $info  = DB::table('expedisi_jemputs')->select('expedisi_jemputs.id')->where('expedisi_jemputs.permintaan_laundry_id',$id)->first();

            DB::delete("delete from expedisi_jemputs where expedisi_jemputs.id =".$info->id);

            DB::delete("delete from expedisi_jemput_images where expedisi_jemput_id = ".$info->id);
            
            // Alert::toast('Pencatatan Jemput Berhasil Dihapus', 'success');
            return redirect()->route('expedisi-jemput');
        } catch (\Throwable $e) {
            echo 'gagal';
            exit();
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jemput');
        }
    }

    // public function getDataMember() {
    //     $data = DB::table('members')
    //     ->select('members.*', 'users.name')
    //     ->join('users', 'users.id', '=', 'members.user_id')
    //     ->whereNull('members.deleted_at')
    //     ->orderBy('users.name', 'ASC')
    //     ->get();
        
    //     return DataTables::of($data)
    //     ->addIndexColumn()
    //     ->rawColumns(['action', 'roles'])
    //     ->make(true);
    // }
}
