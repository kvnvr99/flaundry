<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harga;
use App\Models\Layanan;
use App\Models\Outlet;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MasterDataController extends Controller {

    public function index() {
        $data = [
            'harga' => Harga::count(),
            'layanan' => Layanan::count(),
            'outlet' => Outlet::count(),
            'user' => User::where('is_member', '0')->where('id','!=', 1)->count(),
            'role' => Role::count()
        ];
        // return $data;
        return view('master-data.index', compact('data'));
    }
}
