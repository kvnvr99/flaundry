<?php

namespace App\Http\Controllers\Transaksi;

use DB;
use File;
use Carbon\Carbon;
use App\Models\Harga;
use App\Models\Outlet;
use App\Models\Parfume;
use App\Models\Corporate;
use App\Models\Transaksi;
use App\Models\LogActivity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransaksiImage;
use App\Models\TransaksiDetail;
use App\Models\PermintaanLaundry;
use App\Models\ExpedisiJemputImage;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\RequestLaundryRequest;

class JemputPesananController extends Controller
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
        return view('transaksi.jemput_pesanan.index', compact('outlets', 'parfumes'));
    }

    public function create($id) {

        try {

            $outlets = Outlet::get();
            $parfumes = Parfume::get();
            $images = ExpedisiJemputImage::get();

            $info = DB::table('permintaan_laundries')->select('permintaan_laundries.*', 'users.name as nama', 'parfumes.nama as nama_parfume', 'layanans.nama as nama_layanan','corporate.id as corporate_id', 'corporate.phone')
                                    ->join('corporate', 'corporate.id', '=', 'permintaan_laundries.corporate_id', 'left')
                                    ->join('users', 'users.id', '=', 'corporate.user_id', 'left')
                                    ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id', 'left')
                                    ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id', 'left')
                                    ->where('permintaan_laundries.id', $id)
                                    ->first();

            // $images = DB::table('expedisi_jemput_images')->select('expedisi_jemput_images.*')
            //                         ->join('expedisi_jemputs', 'expedisi_jemputs.id', '=', 'expedisi_jemput_images.expedisi_jemput_id', 'left')
            //                         ->where('expedisi_jemputs.permintaan_laundry_id', $id);

            return view('transaksi.jemput_pesanan.add', compact('info','outlets', 'parfumes', 'images'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('jemput_pesanan');
        }
        
    }

    public function getDataLayanan(Request $request) {
        // $data = Harga::select('id','kode','nama','harga','jenis_item')->where('kategori', $request->kategori);
        $data = Harga::select('id','kode','nama','harga','jenis_item');
        return DataTables::of($data)
        ->addColumn('harga', function ($data) {
            return $data->harga;
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
                'member_id' => 0,
                'outlet_id' => 0,
                'corporate_id' => $request->corporate_id,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'parfume' => $request->parfume,
                'no_handphone' => $request->no_handphone,
                'total' => $total,
                'bayar' => $request->bayar,
                'pembayaran' => $request->pembayaran,
                'note' => $request->note,
                'status' => 'registrasi',
                'is_done' => '1'
            ];

            DB::beginTransaction();
            $permintaan_laundry = PermintaanLaundry::where('id', $request->permintaan_laundry_id)->first();
        
            if (empty($permintaan_laundry->picked_at)) {
                $permintaan_laundry->update([
                    'picked_at' => now(),
                    'picked_by' => null,
                    'status_jemput' => 1
                ]);
            }
            
            $transaksi = Transaksi::create($data);
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
                $detail [] = TransaksiDetail::create($transaksi_detail);
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
                'action'    => 'Add',
                'note'      => Auth::user()->name . ' Telah menambahkan registrasi dengan no ' . $data['kode_transaksi'],
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
            $data = Transaksi::with('TransaksiDetail')->where('kode_transaksi', $kode_transaksi)->first();
            return view('transaksi.jemput_pesanan.faktur', compact('data'));
        } catch (\Throwable $th) {
            Alert::toast($th->getMessage(), 'error');
            return back();
        }
    }

    public function getData() {

        $data = DB::table('permintaan_laundries')
        ->select('permintaan_laundries.*', 'users.name as nama', 'parfumes.nama as nama_parfume', 'layanans.nama as nama_layanan')
        ->join('corporate', 'corporate.id', '=', 'permintaan_laundries.corporate_id', 'left')
        ->join('users', 'users.id', '=', 'corporate.user_id', 'left')
        ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id', 'left')
        ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id', 'left')
        // ->whereNotNull('permintaan_laundries.picked_at') 
        ->whereNull('permintaan_laundries.deleted_at') 
        ->whereRaw('permintaan_laundries.id NOT IN (SELECT ifnull(permintaan_laundry_id,0) FROM transaksis)')
        ->orderBy('permintaan_laundries.layanan_id', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_accept' => route('jemput_pesanan.create', $data->id),
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function history(){
        $parfumes = Parfume::get();
        return view('transaksi.jemput_pesanan.history', compact('parfumes'));
    }

    public function getDataHistory() {

        $data = DB::table('permintaan_laundries')
        ->select('permintaan_laundries.*', 'users.name as nama', 'parfumes.nama as nama_parfume', 'layanans.nama as nama_layanan', 'transaksis.kode_transaksi', 'transaksis.status')
        ->join('transaksis', 'transaksis.permintaan_laundry_id', '=', 'permintaan_laundries.id', 'left')
        ->join('corporate', 'corporate.id', '=', 'permintaan_laundries.corporate_id', 'left')
        ->join('users', 'users.id', '=', 'corporate.user_id', 'left')
        ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id', 'left')
        ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id', 'left')
        // ->whereNotNull('permintaan_laundries.picked_at') 
        ->whereRaw('permintaan_laundries.id IN (SELECT ifnull(permintaan_laundry_id,0) FROM transaksis)')
        ->orderBy('permintaan_laundries.layanan_id', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            $btn = '';
            $btn .= '<a href="'.route('jemput_pesanan.detail',$data->kode_transaksi).'" class="btn btn-sm btn-info waves-effect waves-light mx-1" title="Detail">'.
                '<i class="fas fa-exclamation-circle"></i>'.
                '</a>';
            $btn .= '<a href="'.route('jemput_pesanan.print',$data->kode_transaksi).'" target="_blank" class="btn btn-sm btn-secondary waves-effect waves-light mx-1" title="Print">'.
                '<i class="fa fa-print"></i>'.
                '</a>';
            if ($data->status === 'registrasi') {
                $btn .= '<a href="'.route('jemput_pesanan.edit',$data->kode_transaksi).'" class="btn btn-sm btn-warning waves-effect waves-light mx-1" title="Edit">'.
                        '<i class="fas fa-align-left"></i>'.
                    '</a>';
                }
            return $btn;
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function edit($kode_transaksi) {

        try {

            $outlets = Outlet::get();
            $parfumes = Parfume::get();
            $images = ExpedisiJemputImage::get();

            $info = DB::table('transaksis')
                ->select('transaksis.*')
                ->leftJoin('corporate', 'corporate.id', '=', 'transaksis.corporate_id')
                ->leftJoin('users', 'users.id', '=', 'corporate.user_id')
                ->where('transaksis.kode_transaksi', $kode_transaksi)
                ->first();

            if (!$info) {
                Alert::toast('Tidak ada transaksi dengan kode ' . $kode_transaksi, 'error');
                return redirect()->route('jemput_pesanan.history');
            }

            if ($info->status != 'registrasi') {
                Alert::toast('Transaksi sudah di proses', 'error');
                return redirect()->route('jemput_pesanan.history');
            }


            $transaksi_detail = TransaksiDetail::select(DB::raw('transaksi_details.*, hargas.nama as nama_harga, hargas.harga'))
                                    ->where('transaksi_id', $info->id)
                                    ->join('hargas', 'hargas.id', '=', 'transaksi_details.harga_id', 'left')
                                    ->get();

            $images = DB::table('transaksi_images')->select('transaksi_images.*')
                                    ->where('transaksi_images.transaksi_id', $info->id)->get();

            return view('transaksi.jemput_pesanan.edit', compact('info','outlets', 'parfumes', 'images', 'transaksi_detail'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('jemput_pesanan.history');
        }
    }

    public function update(Request $request) {
        try {
            if(!isset($request->layanan))
                return response()->json([ 'status' => false, 'err' => 'empty_layanan', 'msg' => 'Pilih Transaksi' ], 200);

            $total = array_sum(array_column($request->layanan, 'total'));
            $data = [
                'updated_by' => Auth::user()->id,
                'parfume' => $request->parfume,
                'total' => $total,
                'note' => $request->note,
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
            $images = ExpedisiJemputImage::get();

            $info = DB::table('transaksis')->select('transaksis.*')
                                    ->join('corporate', 'corporate.id', '=', 'transaksis.corporate_id', 'left')
                                    ->join('users', 'users.id', '=', 'corporate.user_id', 'left')
                                    ->where('transaksis.kode_transaksi', $kode_transaksi)
                                    ->first();

            if (!$info) {
                Alert::toast('Tidak ada transaksi dengan kode ' . $kode_transaksi, 'error');
                return redirect()->route('jemput_pesanan.history');
            }

            $transaksi_detail = TransaksiDetail::select(DB::raw('transaksi_details.*, hargas.nama as nama_harga, hargas.harga'))
                                    ->where('transaksi_id', $info->id)
                                    ->join('hargas', 'hargas.id', '=', 'transaksi_details.harga_id', 'left')
                                    ->get();

            $images = DB::table('transaksi_images')->select('transaksi_images.*')
                                    ->where('transaksi_images.transaksi_id', $info->id)->get();

            return view('transaksi.jemput_pesanan.detail', compact('info','outlets', 'parfumes', 'images', 'transaksi_detail'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('jemput_pesanan.history');
        }
    }

}
