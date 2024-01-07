<?php

namespace App\Http\Controllers\Transaksi;

use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RequestLaundryRequest;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiImage;
use App\Models\ExpedisiJemputImage;
use App\Models\Harga;
use App\Models\Outlet;
use App\Models\Parfume;
use App\Models\LogActivity;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class RequestLaundryController extends Controller
{

    protected $model, $detail, $images;

    public function __construct(Transaksi $Transaksi, TransaksiDetail $TransaksiDetail, TransaksiImage $TransaksiImage, ExpedisiJemputImage $ExpedisiJemputImage) {
        $this->model = new BaseRepository($Transaksi);
        $this->detail = new BaseRepository($TransaksiDetail);
        $this->images = new BaseRepository($TransaksiImage);
        $this->expedisi_images = new BaseRepository($ExpedisiJemputImage);
        $this->middleware('auth');
    }

    public function index() {
        $outlets = Outlet::get();
        $parfumes = Parfume::get();
        return view('transaksi.request-laundry.index', compact('outlets', 'parfumes'));
    }

    public function create($id) {

        try {

            $outlets = Outlet::get();
            $parfumes = Parfume::get();

            $info = DB::table('permintaan_laundries')->select('permintaan_laundries.*', 'users.name as nama', 'parfumes.nama as nama_parfume', 'layanans.nama as nama_layanan', 'members.phone')
                                    ->join('members', 'members.id', '=', 'permintaan_laundries.member_id', 'left')
                                    ->join('users', 'users.id', '=', 'members.user_id', 'left')
                                    ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id', 'left')
                                    ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id', 'left')
                                    ->where('permintaan_laundries.id', $id)
                                    ->first();

            $images = DB::table('expedisi_jemput_images')->select('expedisi_jemput_images.*')
                                    ->join('expedisi_jemputs', 'expedisi_jemputs.id', '=', 'expedisi_jemput_images.expedisi_jemput_id', 'left')
                                    ->where('expedisi_jemputs.permintaan_laundry_id', $id)->get();

            return view('transaksi.request-laundry.add', compact('info','outlets', 'parfumes', 'images'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('request-laundry');
        }
        
    }

    public function getDataLayanan(Request $request) {
        if ($request->member == 'member') {
            $data = Harga::select('id','kode','nama','harga_member', 'jenis_item')->where('kategori', $request->kategori);
        } else {
            $data = Harga::select('id','kode','nama','harga','jenis_item')->where('kategori', $request->kategori);
        }
        return DataTables::of($data)
        ->addColumn('harga', function ($data) {
            if (isset($data->harga_member)){
                return $data->harga_member;
            } else {
                return $data->harga;
            }
        })
        ->addIndexColumn()
        ->rawColumns(['harga'])
        ->make(true);
    }

    public function store(Request $request) {
        try {
            $kode_transaksi = date("dmy");
            if(!isset($request->layanan))
                return response()->json([ 'status' => false, 'err' => 'empty_layanan', 'msg' => 'Pilih Transaksi' ], 200);

            $total = array_sum(array_column($request->layanan, 'total'));
            $data = [
                'kode_transaksi' => $kode_transaksi.strtoupper(Str::random(5)),
                'kasir_id' => Auth::user()->id,
                'permintaan_laundry_id' => $request->permintaan_laundry_id,
                'outlet_id' => $request->outlet,
                'member_id' => $request->member_id,
                'kategori' => $request->kategori,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'parfume' => $request->parfume,
                'no_handphone' => $request->no_handphone,
                'total' => $total,
                'bayar' => str_replace(['Rp ','.'], '', $request->bayar),
                'pembayaran' => $request->pembayaran,
                'note' => $request->note,
                'status' => 'registrasi',
                'is_done' => '1'
            ];
            DB::beginTransaction();
            $transaksi = $this->model->store($data);
            $detail = [];
            foreach ($request->layanan as $layanan) {
                $transaksi_detail = [
                    "transaksi_id" => $transaksi->id,
                    "harga_id" => $layanan['id'],
                    "jumlah" => str_replace('.', '', $layanan['qty_satuan']),
                    "harga_satuan" => $layanan['harga'],
                    "harga_jumlah" => str_replace('.', '', $layanan['qty_satuan']) * $layanan['harga'],
                    "qty_special_treatment" => $layanan['qty_special_treatment'] ? str_replace('.', '', $layanan['qty_special_treatment']) : 0,
                    "harga_special_treatment" => $layanan['harga_special_treatment'],
                    'harga_jumlah_special_treatment' => $layanan['qty_special_treatment'] ? str_replace('.', '', $layanan['qty_special_treatment']) : 0 * $layanan['harga_special_treatment'],
                    "total" => $layanan['total']
                ];
                $detail [] = $this->detail->store($transaksi_detail);
            }
            if($request->hasfile('images')) {
                $images = [];
                foreach($request->file('images') as $file) {
                    $image = [];
                    $image['transaksi_id'] = $transaksi->id;
                    $image['image'] = $file->store('transaksi/registrasi', 'public');
                    $images [] = $this->images->store($image);
                }
            }
            $outlet_name = Outlet::where('id', $data['outlet_id'])->firstOrFail();
            LogActivity::create([
                'user_id'   => Auth::user()->id,
                'modul'     => 'Registrasi',
                'model'     => 'Transaksi',
                'action'    => 'Add',
                'note'      => Auth::user()->name . ' Telah menambahkan registrasi dengan no ' . $data['kode_transaksi'] . ' di outlet ' . $outlet_name->nama,
                'old_data'  => null,
                'new_data'  => json_encode($data),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'kode_transaksi' => $transaksi->kode_transaksi
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

    public function print($kode_transaksi) {
        try {
            $data = Transaksi::with('TransaksiDetail', 'outlet')->where('kode_transaksi', $kode_transaksi)->first();
            return view('transaksi.request-laundry.faktur', compact('data'));
        } catch (\Throwable $th) {
            Alert::toast($th->getMessage(), 'error');
            return back();
        }
    }

    public function getData() {

        $data = DB::table('permintaan_laundries')
        ->select('permintaan_laundries.*', 'users.name as nama', 'parfumes.nama as nama_parfume', 'layanans.nama as nama_layanan')
        ->join('members', 'members.id', '=', 'permintaan_laundries.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id', 'left')
        ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id', 'left')
        ->whereNotNull('permintaan_laundries.picked_at')
        ->whereNull('permintaan_laundries.deleted_at') 
        ->whereRaw('permintaan_laundries.id NOT IN (SELECT ifnull(permintaan_laundry_id,0) FROM transaksis)')
        ->where('permintaan_laundries.corporate_id','=', 0)
        ->orderBy('permintaan_laundries.layanan_id', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_accept' => route('request-laundry.create', $data->id),
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }
    
    public function history() {
        $outlets = Outlet::get();
        $parfumes = Parfume::get();
        return view('transaksi.request-laundry.history', compact('outlets', 'parfumes'));
    }

    public function getDataHistory() {

        $data = DB::table('permintaan_laundries')
        ->select('permintaan_laundries.*', 'users.name as nama', 'parfumes.nama as nama_parfume', 'layanans.nama as nama_layanan', 'transaksis.kode_transaksi', 'transaksis.status')
        ->join('transaksis', 'transaksis.permintaan_laundry_id', '=', 'permintaan_laundries.id', 'left')
        ->join('members', 'members.id', '=', 'permintaan_laundries.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id', 'left')
        ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id', 'left')
        ->whereNotNull('permintaan_laundries.picked_at')
        ->whereNull('permintaan_laundries.deleted_at') 
        ->where('permintaan_laundries.corporate_id','=', 0)
        ->whereRaw('permintaan_laundries.id IN (SELECT ifnull(permintaan_laundry_id,0) FROM transaksis)')
        ->orderBy('permintaan_laundries.layanan_id', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            $btn = '';
            $btn .= '<a href="'.route('request-laundry.detail',$data->kode_transaksi).'" class="btn btn-sm btn-info waves-effect waves-light mx-1" title="Detail">'.
                '<i class="fas fa-exclamation-circle"></i>'.
                '</a>';
            $btn .= '<a href="'.route('request-laundry.print',$data->kode_transaksi).'" target="_blank" class="btn btn-sm btn-secondary waves-effect waves-light mx-1" title="Print">'.
                '<i class="fa fa-print"></i>'.
                '</a>';
            if ($data->status === 'registrasi') {
                $btn .= '<a href="'.route('request-laundry.edit',$data->kode_transaksi).'" class="btn btn-sm btn-warning waves-effect waves-light mx-1" title="Edit">'.
                        '<i class="fas fa-align-left"></i>'.
                    '</a>';
                }
            return $btn;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function edit($kode_transaksi) {

        try {

            $outlets = Outlet::get();
            $parfumes = Parfume::get();

            $info = DB::table('transaksis')
                ->select('transaksis.*')
                ->leftJoin('corporate', 'corporate.id', '=', 'transaksis.corporate_id')
                ->leftJoin('users', 'users.id', '=', 'corporate.user_id')
                ->where('transaksis.kode_transaksi', $kode_transaksi)
                ->first();

            
            if (!$info) {
                Alert::toast('Tidak ada transaksi dengan kode ' . $kode_transaksi, 'error');
                return redirect()->route('request-laundry.history');
            }

            if ($info->status !== 'registrasi') {
                Alert::toast('Transaksi sudah di proses', 'error');
                return redirect()->route('request-laundry.history');
            }


            $transaksi_detail = TransaksiDetail::select(DB::raw('transaksi_details.*, hargas.nama as nama_harga, hargas.harga, hargas.harga_member'))
                                    ->where('transaksi_id', $info->id)
                                    ->join('hargas', 'hargas.id', '=', 'transaksi_details.harga_id', 'left')
                                    ->get();

            $images = DB::table('transaksi_images')->select('transaksi_images.*')
                                    ->where('transaksi_images.transaksi_id', $info->id)->get();

            return view('transaksi.request-laundry.edit', compact('info','outlets', 'parfumes', 'images', 'transaksi_detail'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('request-laundry.history');
        }
    }

    public function update(Request $request) {
        try {
            if(!isset($request->layanan))
                return response()->json([ 'status' => false, 'err' => 'empty_layanan', 'msg' => 'Pilih Transaksi' ], 200);

            $total = array_sum(array_column($request->layanan, 'total'));
            $data = [
                'updated_by' => Auth::user()->id,
                'outlet_id' => $request->outlet,
                'member_id' => $request->member_id ? $request->member_id : 0,
                'kategori' => $request->kategori,
                'parfume' => $request->parfume,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_handphone' => $request->no_handphone,
                'total' => $total,
                'note' => $request->note,
                'bayar' => str_replace(['Rp ','.'], '', $request->bayar),
                'pembayaran' => $request->pembayaran,
                'status' => 'registrasi',
                'is_done' => '1'
            ];

            DB::beginTransaction();
            
            $transaksi = Transaksi::find($request->id);
            $transaksi->update($data);
            $detail = [];
            foreach ($request->layanan as $layanan) {
                $transaksi_detail = [
                    "transaksi_id" => $transaksi->id,
                    "harga_id" => $layanan['id'],
                    "kode_layanan" => $layanan['kode_layanan'],
                    "jumlah" => str_replace('.', '', $layanan['qty_satuan']),
                    "harga_satuan" => $layanan['harga'],
                    "harga_jumlah" => str_replace('.', '', $layanan['qty_satuan']) * $layanan['harga'],
                    "qty_special_treatment" => $layanan['qty_special_treatment'] ? str_replace('.', '', $layanan['qty_special_treatment']) : 0,
                    "harga_special_treatment" => $layanan['harga_special_treatment'],
                    'harga_jumlah_special_treatment' => 0,
                    "total" => $layanan['total']
                ];
                if (!empty($layanan['transaksi_detail_id'])) {
                    # code...
                    $transaksi_detail_instance = TransaksiDetail::find($layanan['transaksi_detail_id']);
                    $transaksi_detail_instance->update($transaksi_detail);

                } else {
                    # code...
                    $detail [] = TransaksiDetail::create($transaksi_detail);
                }
                
            }
            if($request->hasfile('images')) {
                $images = [];
                foreach($request->file('images') as $file) {
                    $image = [];
                    $image['transaksi_id'] = $transaksi->id;
                    $image['image'] = $file->store('transaksi/registrasi', 'public');
                    $images [] = TransaksiImage::create($image);
                }
            }
            // $corporate = Corporate::where('id', $data['corporate_id'])->firstOrFail();
            LogActivity::create([
                'user_id'   => Auth::user()->id,
                'modul'     => 'Registrasi',
                'model'     => 'Transaksi',
                'action'    => 'Edit',
                'note'      => Auth::user()->name . ' Telah memperbarui registrasi dengan no ' . $transaksi['kode_transaksi'],
                'old_data'  => null,
                'new_data'  => json_encode($data),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'kode_transaksi' => $transaksi->kode_transaksi
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

    public function deleteLayanan(Request $request){
        try {
            DB::beginTransaction();
            $data = TransaksiDetail::find($request->id);
            if (!$data) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                ], 404);
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
    
    public function deleteImg(Request $request){
        try {
            DB::beginTransaction();
            $data = TransaksiImage::find($request->id);
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

    public function detail($kode_transaksi) {

        try {

            $outlets = Outlet::get();
            $parfumes = Parfume::get();

            $info = DB::table('transaksis')->select('transaksis.*')
                                    ->join('corporate', 'corporate.id', '=', 'transaksis.corporate_id', 'left')
                                    ->join('users', 'users.id', '=', 'corporate.user_id', 'left')
                                    ->where('transaksis.kode_transaksi', $kode_transaksi)
                                    ->first();

            if (!$info) {
                Alert::toast('Tidak ada transaksi dengan kode ' . $kode_transaksi, 'error');
                return redirect()->route('request-laundry.history');
            }

            $transaksi_detail = TransaksiDetail::select(DB::raw('transaksi_details.*, hargas.nama as nama_harga, hargas.harga, hargas.harga_member'))
                                    ->where('transaksi_id', $info->id)
                                    ->join('hargas', 'hargas.id', '=', 'transaksi_details.harga_id', 'left')
                                    ->get();

            $images = DB::table('transaksi_images')->select('transaksi_images.*')
                                    ->where('transaksi_images.transaksi_id', $info->id)->get();

            return view('transaksi.request-laundry.detail', compact('info','outlets', 'parfumes', 'images', 'transaksi_detail'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('request-laundry.history');
        }
    }
}
