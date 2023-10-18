<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\KasirRequest;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiImage;
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

class KasirController extends Controller
{

    protected $model, $detail, $images;

    public function __construct(Transaksi $Transaksi, TransaksiDetail $TransaksiDetail, TransaksiImage $TransaksiImage) {
        $this->model = new BaseRepository($Transaksi);
        $this->detail = new BaseRepository($TransaksiDetail);
        $this->images = new BaseRepository($TransaksiImage);
        $this->middleware('auth');
    }

    public function index() {
        $outlets = Outlet::get();
        $parfumes = Parfume::get();
        return view('transaksi.registrasi.index', compact('outlets', 'parfumes'));
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
                'outlet_id' => $request->outlet,
                'member_id' => $request->member_id,
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
            $transaksi = $this->model->store($data);
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
            return view('transaksi.registrasi.faktur', compact('data'));
        } catch (\Throwable $th) {
            Alert::toast($th->getMessage(), 'error');
            return back();
        }
    }
}
