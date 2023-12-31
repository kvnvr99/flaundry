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

class JemputNonPesananController extends Controller
{

    protected $model, $detail, $images;

    public function __construct(Transaksi $Transaksi, TransaksiDetail $TransaksiDetail, TransaksiImage $TransaksiImage) {
        $this->model = new BaseRepository($Transaksi);
        $this->detail = new BaseRepository($TransaksiDetail);
        $this->images = new BaseRepository($TransaksiImage);
        $this->middleware('auth');
    }

    public function index() {
        $parfumes = Parfume::get();
        return view('transaksi.jemput_non_pesanan.index', compact('parfumes'));
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
                'permintaan_laundry_id' => null,
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
                    "kode_layanan" => $layanan['kode_layanan'],
                    "jumlah" => str_replace('.', '', $layanan['qty_satuan']),
                    "harga_satuan" => $layanan['harga'],
                    "harga_jumlah" => str_replace('.', '', $layanan['qty_satuan']) * $layanan['harga'],
                    "qty_special_treatment" => str_replace('.', '', $layanan['qty_special_treatment']),
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
            $data = Transaksi::with('TransaksiDetail', 'outlet')->where('kode_transaksi', $kode_transaksi)->first();
            return view('transaksi.jemput_non_pesanan.faktur', compact('data'));
        } catch (\Throwable $th) {
            Alert::toast($th->getMessage(), 'error');
            return back();
        }
    }

    public function history(){
        return view('transaksi.jemput_non_pesanan.history');
    }

    public function getDataHistory(){
        $data = Transaksi::select(DB::raw('transaksis.nama, transaksis.kode_transaksi'))
                        ->where('member_id', 0)
                        ->where('outlet_id', 0)
                        ->whereNull('permintaan_laundry_id')
                        ->whereNotNull('corporate_id')
                        ->get();

        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            $btn = '<a href="/jemput-non-pesanan/print/' . $data->kode_transaksi . '" target="_blank" class="btn btn-sm btn-secondary waves-effect waves-light" title="Print">'.
                        '<i class="fa fa-print"></i>'.
                    '</a>';
            return $btn;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }
}
