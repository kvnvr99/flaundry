<?php

namespace App\Http\Controllers\MasterData;

use App\Models\User;
use App\Models\Harga;
use App\Models\Outlet;
use App\Models\Layanan;
use App\Models\Parfume;
use App\Models\Corporate;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class MasterDataController extends Controller {

    public function index() {
        $data = [
            'corporate' => Corporate::count(),
            'parfume' => Parfume::count(),
            'harga' => Harga::count(),
            'layanan' => Layanan::count(),
            'outlet' => Outlet::count(),
            'user' => User::where('is_member', '0')->where('is_corporate', '0')->where('id','!=', 1)->count(),
            'role' => Role::count()
        ];
        // return $data;
        return view('master-data.index', compact('data'));
    }
}
