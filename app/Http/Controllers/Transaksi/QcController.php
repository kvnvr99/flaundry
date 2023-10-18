<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiImage;
use App\Models\LogActivity;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use DB;

class QcController extends Controller {

    protected $model, $detail, $images;

    public function __construct(Transaksi $Transaksi, TransaksiDetail $TransaksiDetail, TransaksiImage $TransaksiImage) {
        $this->model = new BaseRepository($Transaksi);
        $this->detail = new BaseRepository($TransaksiDetail);
        $this->images = new BaseRepository($TransaksiImage);
        $this->middleware('auth');
    }

    public function index() {
        return view('transaksi.qc.index');
    }

    public function getData() {
        $data = Transaksi::with('TransaksiDetail', 'outlet')->where('status', 'registrasi')->where('is_done', '1')->get();
        return DataTables::of($data)
        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'valid' => $data->id,
                'invalid' => $data->id
            ]);
        })
        ->addColumn('items', function ($data) {
            $roles = $data->permissions()->get();
            $items = '';
            $no = 1;
            foreach ($data->TransaksiDetail as $detail) {
                $items .= $no.' '.$detail->harga->nama.'<br>';
                $no++;
            }
            return $items;
        })
        ->addColumn('quantity_satuan', function ($data) {
            return view('component.action', [
                'model' => $data,
                'input_satuan' => $data->id
            ]);

        })
        ->addColumn('quantity_kg', function ($data) {
            return view('component.action', [
                'model' => $data,
                'input_kg' => $data->id
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'items', 'quantity_satuan, quantity_kg'])
        ->make(true);
    }

    public function store(Request $request) {

        try {
            $this->validate($request, [ 'quantity_satuan' => 'required|numeric', 'quantity_kg' => 'required|numeric' ]);
            $data = [
                'quantity_qc' => $request->quantity_satuan,
                'kg_qc' => $request->quantity_kg,
                'status' => 'qc',
                'is_done' => '1',
                'qc_id' => Auth::user()->id
            ];
            DB::beginTransaction();
            $updated = $this->model->update($request->id, $data);
            LogActivity::create([
                'user_id'   => Auth::user()->id,
                'modul'     => 'QC',
                'model'     => 'Transaksi',
                'action'    => 'Update',
                'note'      => Auth::user()->name . ' Telah memperbaharui transaksi ' . $updated['kode_transaksi'],
                'old_data'  => null,
                'new_data'  => json_encode($data),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
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
}
