<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use DB;

class OutletSeeder extends Seeder {

    public function run() {
        DB::table('outlets')->delete();

        $index_outlet = 0;
        $id = 1;
        $outlet = [
            'RP',
            'CIJAMBE',
            'CIGENDING',
            'SARIMAS',
            'ANTAPANI 1',
            'ANTAPANI 2',
            'SAPAN'
        ];

        do {
            Outlet::create([
                'id' => $id,
                'nama' => $outlet[$index_outlet],
                'alamat' =>  $outlet[$index_outlet],
                'created_by' => 0
            ]);
            $index_outlet++;
            $id++;
        } while ($index_outlet < sizeOf($outlet));
    }
}
