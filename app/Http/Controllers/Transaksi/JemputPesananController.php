<?php

namespace App\Http\Controllers\Transaksi;

use DB;
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
            $transaksi = Transaksi::create($data);
            $detail = [];
            foreach ($request->layanan as $layanan) {
                $transaksi_detail = [
                    "transaksi_id" => $transaksi->id,
                    "harga_id" => $layanan['id'],
                    "jumlah" => $layanan['qty_satuan'],
                    "harga_satuan" => $layanan['harga'],
                    "harga_jumlah" => $layanan['qty_satuan'] * $layanan['harga'],
                    "qty_special_treatment" => $layanan['qty_special_treatment'],
                    "harga_special_treatment" => $layanan['harga_special_treatment'],
                    'harga_jumlah_special_treatment' => $layanan['qty_special_treatment'] * $layanan['harga_special_treatment'],
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

}
