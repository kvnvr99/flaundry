<?php

namespace App\Http\Controllers\Transaksi;

use DB;
use Carbon\Carbon;
use File;

use Illuminate\Http\Request;
use App\Models\ExpedisiJemput;
use App\Models\PermintaanLaundry;
use Spatie\Permission\Models\Role;
use App\Models\ExpedisiJemputImage;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ExpedisiJemputRequest;
use App\Http\Requests\ExpedisiJemputRequestUpdate;

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
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        $data = PermintaanLaundry::select(
            'permintaan_laundries.*',
            \DB::raw("(CASE WHEN permintaan_laundries.id IN (SELECT permintaan_laundry_id FROM expedisi_jemputs) THEN 'sudah' ELSE '-' END) AS status"),
            \DB::raw("CASE WHEN permintaan_laundries.member_id = 0 THEN 'corporate' ELSE 'members' END AS join_type"),
            \DB::raw("COALESCE(corporate_user.name, users.name) AS name")
        )
            ->leftJoin('members', 'members.id', '=', 'permintaan_laundries.member_id')
            ->leftJoin('users', 'users.id', '=', 'members.user_id')
            ->leftJoin('corporate', 'corporate.id', '=', 'permintaan_laundries.corporate_id')
            ->leftJoin('users as corporate_user', 'corporate_user.id', '=', 'corporate.user_id')
            ->whereNull('permintaan_laundries.deleted_at')
            ->whereRaw('permintaan_laundries.id NOT IN (SELECT ifnull(permintaan_laundry_id,0) FROM transaksis)')
            // ->where(function ($query) use ($today, $yesterday, $tomorrow) {
            //     $query->where(function ($subquery) use ($today, $yesterday, $tomorrow) {
            //         $subquery->whereDate('permintaan_laundries.created_at', '=', $today)
            //             ->orWhereDate('permintaan_laundries.created_at', '=', $yesterday)
            //             ->orWhereDate('permintaan_laundries.created_at', '=', $tomorrow);
            //     })
            //     ->orWhere(function ($subquery) {
            //         $subquery->where('picked_at', null);
            //     });
            // })
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
                if($request->hasfile('images')) {
                    $images = [];
                    foreach($request->file('images') as $file) {
                        $image = [];
                        $image['expedisi_jemput_id'] = $expedisijemput->id;
                        $image['image'] = $file->store('transaksi/penjemputan', 'public');
                        $images [] = $this->images->store($image);
                    }
                }
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
            // $data['detail'] = DB::table('permintaan_laundries')
            // ->select('permintaan_laundries.*', 'users.name','expedisi_jemputs.titip_saldo', 'expedisi_jemputs.catatan', 'expedisi_jemputs.id as expedisi_jemput_id', 'expedisi_jemputs.image')
            // ->join('members', 'members.id', '=', 'permintaan_laundries.member_id')
            // ->join('users', 'users.id', '=', 'members.user_id')
            // ->join('expedisi_jemputs', 'expedisi_jemputs.permintaan_laundry_id', '=', 'permintaan_laundries.id','left')
            // ->where('permintaan_laundries.id',$id)
            // ->get();
            $data['detail'] = PermintaanLaundry::select(
                'permintaan_laundries.*', 'expedisi_jemputs.titip_saldo', 'expedisi_jemputs.catatan', 'expedisi_jemputs.id as expedisi_jemput_id', 'expedisi_jemputs.image',
                \DB::raw("CASE WHEN permintaan_laundries.member_id = 0 THEN 'corporate' ELSE 'members' END AS join_type"),
                \DB::raw("COALESCE(corporate_user.name, users.name) AS name"),
            )
            ->leftJoin('members', 'members.id', '=', 'permintaan_laundries.member_id')
            ->leftJoin('users', 'users.id', '=', 'members.user_id')
            ->leftJoin('corporate', 'corporate.id', '=', 'permintaan_laundries.corporate_id')
            ->leftJoin('users as corporate_user', 'corporate_user.id', '=', 'corporate.user_id')
            ->leftJoin('expedisi_jemputs', 'expedisi_jemputs.permintaan_laundry_id', '=', 'permintaan_laundries.id')
            ->where('permintaan_laundries.id',$id)
            ->get();

            $images = DB::table('expedisi_jemput_images')->select('expedisi_jemput_images.*')
                                    ->join('expedisi_jemputs', 'expedisi_jemputs.id', '=', 'expedisi_jemput_images.expedisi_jemput_id', 'left')
                                    ->where('expedisi_jemputs.permintaan_laundry_id', $id)
                                    ->get();

            return view('transaksi.expedisi-jemput.form', compact('data', 'images'));
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

    public function deleteImg(Request $request){
        try {
            DB::beginTransaction();
            $data = ExpedisiJemputImage::find($request->id);
            if (!$data) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                ], 404);
            }
            $image_path = "./storage/" . $data->image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $data->delete();
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'err' => 'system_error',
                'msg' => $th->getMessage()
            ], 200);
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
