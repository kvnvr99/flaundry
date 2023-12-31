<?php

namespace Database\Seeders;

use DB;
// use App\Models\Master\Menu;
use App\Models\Member;
use App\Models\Corporate;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete semua data user, role, permission
        DB::table('users')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $index = 0;
        $menus = [
            'dashboard',
            'infogram',
            'laporan',
            'registrasi',
            'data-member',
            'topup-member',
            'quality-control',
            'cuci',
            'pengeringan',
            'setrika',
            'jadwal-jemput',
            'jadwal-antar',
            'jemput-barang',
            'antar-barang',
            'master-data',

            'home-member',
            'pesanan-baru',
            'list-transaksi',
            'history-transaksi',

            'data-corporate',
            'jemput_pesanan',
            'jemput_non_pesanan',

            'home-corporate',
            'list-transaksi-corporate',
            'history-transaksi-corporate',


        ];

        // BUAT PERMISSION
        do {
            Permission::create([
                'name' => $menus[$index]
            ]);
            $index++;
        } while ($index < sizeOf($menus));

        // BUAT ROLE
        $dev = Role::create(['name' => 'Maintener']);
        $dev->givePermissionTo(Permission::all()); //AKSES SEMUA PERMISSION

        $sa = Role::create(['name' => 'Super Admin']);
        $sa->givePermissionTo([
            'dashboard',
            'infogram',
            'laporan',
            'registrasi',
            'data-member',
            'topup-member',
            'data-corporate',
            'quality-control',
            'cuci',
            'pengeringan',
            'setrika',
            'jadwal-jemput',
            'jadwal-antar',
            'jemput-barang',
            'antar-barang',
            'master-data',
            'jemput_pesanan',
            'jemput_non_pesanan'
        ]); // AKSES PERMISSON ADMIN
        $member = Role::create(['name' => 'Member']);
        $member->givePermissionTo([
            'home-member',
            'list-transaksi',
            'history-transaksi'
        ]); // AKSES PERMISSON MEMBER
        $member_corporate = Role::create(['name' => 'Member-Corporate']);
        $member_corporate->givePermissionTo([
            'home-member',
            'pesanan-baru',
            'list-transaksi',
            'history-transaksi'
        ]); // AKSES PERMISSON MEMBER CORPORATE

        $Manager = Role::create(['name' => 'Manager']);
        $Manager->givePermissionTo([
            'dashboard',
            'infogram'
        ]);
        $Supervisior = Role::create(['name' => 'Supervisior']);
        $Supervisior->givePermissionTo([
            'dashboard',
            'infogram'
        ]);
        $Staff = Role::create(['name' => 'Staff']);
        $Staff->givePermissionTo([
            'dashboard',
            'infogram'
        ]);
        $Expedisi = Role::create(['name' => 'Expedisi']);
        $Expedisi->givePermissionTo([
            'dashboard',
            'infogram'
        ]);
        $corporate = Role::create(['name' => 'Corporate']);
        $corporate->givePermissionTo([
            'home-corporate',
            'list-transaksi-corporate',
            'history-transaksi-corporate'
        ]); // AKSES PERMISSON MEMBER

        // BUAT USER
        $user = \App\Models\User::factory()->create([
            'id' => 1,
            'name' => 'Maintener',
            'email' => 'maintener@example.com',
            'password' => Hash::make('asdw1234'),
            'qr_code' => Hash::make('asdw1234')
        ]);
        $user->assignRole($dev);

        $user = \App\Models\User::factory()->create([
            'id' => 2,
            'name' => 'Super Admin',
            'email' => 'super_admin@mail.com',
            'password' => Hash::make('password'),
            'qr_code' => Hash::make('password')
        ]);
        $user->assignRole($sa);

        $user = \App\Models\User::factory()->create([
            'name' => 'Supervisior',
            'email' => 'supervisior@mail.com',
            'password' => Hash::make('password'),
            'qr_code' => Hash::make('password')
        ]);
        $user->assignRole($Supervisior);

        $user = \App\Models\User::factory()->create([
            'name' => 'corporate',
            'is_corporate' => 1,
            'email' => 'corporate@mail.com',
            'password' => Hash::make('password'),
            'qr_code' => Hash::make('password')
        ]);
        $user->assignRole($corporate);
        $data_corporate = Corporate::create([
            'user_id' => $user->id,
            'phone' => '087798889924',
            'address' => 'jl ciwastra',
            'balance' => 0,
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'member',
            'is_member' => '1',
            'email' => 'member@mail.com',
            'password' => Hash::make('password'),
            'qr_code' => Hash::make('password')
        ]);
        $user->assignRole($member);
        $data_member = Member::create([
            'user_id' => $user->id,
            'phone' => '087798889924',
            'address' => 'jl ciwastra',
            'balance' => 0,
        ]);

    }
}
