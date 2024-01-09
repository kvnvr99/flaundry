<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

//Master Data
use App\Http\Controllers\ClearController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Transaksi\QcController;
use App\Http\Controllers\Transaksi\CuciController;
use App\Http\Controllers\MasterData\RoleController;
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\Transaksi\KasirController;
use App\Http\Controllers\Transaksi\TopupController;

//Transaksi
use App\Http\Controllers\MasterData\HargaController;
use App\Http\Controllers\MasterData\MemberController;
use App\Http\Controllers\MasterData\OutletController;
use App\Http\Controllers\Transaksi\SetrikaController;
use App\Http\Controllers\MasterData\LayananController;
use App\Http\Controllers\MasterData\ParfumeController;
use App\Http\Controllers\Laporan\LaporanAgenController;
use App\Http\Controllers\Laporan\LaporanAbsenController;
use App\Http\Controllers\MasterData\CorporateController;
use App\Http\Controllers\Laporan\LaporanMemberController;
use App\Http\Controllers\Laporan\LaporanOutletController;
use App\Http\Controllers\MasterData\MasterDataController;

//Member
use App\Http\Controllers\Member\HistoryLaundryController;
use App\Http\Controllers\Transaksi\PengeringanController;

//Laporan
use App\Http\Controllers\Infogram\InfogramOutletController;
use App\Http\Controllers\Laporan\LaporanExpedisiController;
use App\Http\Controllers\Transaksi\ExpedisiAntarController;
use App\Http\Controllers\Transaksi\JemputPesananController;
use App\Http\Controllers\Transaksi\RekapComplainController;
use App\Http\Controllers\Laporan\LaporanCorporateController;

use App\Http\Controllers\Member\PermintaanLaundryController;
use App\Http\Controllers\Transaksi\ExpedisiJemputController;
use App\Http\Controllers\Transaksi\RequestLaundryController;
use App\Http\Controllers\Infogram\InfogramExpedisiController;
use App\Http\Controllers\Laporan\LaporanFrenchaiseController;
use App\Http\Controllers\Transaksi\JemputNonPesananController;
use App\Http\Controllers\Transaksi\ExpedisiJadwalAntarController;
use App\Http\Controllers\Transaksi\ExpedisiJadwalJemputController;

Route::get('/seeder/permission', function () {
    Artisan::call('db:seed', [
        '--class' => 'PermissionsDemoSeeder',
    ]);

    return redirect('/home');
});

Route::prefix('clear')->group(function () {
    Route::get('/all', [ClearController::class, 'clearOptimize'])->name('clear.all');
    Route::get('/config', [ClearController::class, 'clearConfig'])->name('clear.config');
    Route::get('/cache', [ClearController::class, 'clearCache'])->name('clear.cache');
    Route::get('/migrate', [ClearController::class, 'migrate'])->name('migrate');
    Route::get('/fresh', [ClearController::class, 'migrateFresh'])->name('migrate.fresh');
    Route::get('/seeder', [ClearController::class, 'seeder'])->name('seeder');
    Route::get('/cart', [CartController::class, 'clearCart'])->name('clear_cart');
    Route::get('/storage', [ClearController::class, 'storageLink'])->name('storage');
});

Route::get('/', function() {
    return redirect('/login');
});
Route::post('login-qr', [LoginController::class, 'loginQR'])->name('login-qr');
Auth::routes(['register' => false]);
Route::middleware(['auth'])->group(function () {

    // infogram general
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/infogram', [App\Http\Controllers\HomeController::class, 'infogram'])->name('infogram');
    Route::get('/laporan', [App\Http\Controllers\HomeController::class, 'laporan'])->name('laporan');
    Route::post('/like/{id}', [App\Http\Controllers\HomeController::class, 'like'])->name('like');
    Route::post('/dislike/{id}', [App\Http\Controllers\HomeController::class, 'dislike'])->name('dislike');

    //infogram expedisi
    Route::get('/infogram-outlet', [App\Http\Controllers\HomeController::class, 'infogramExpedisi'])->name('infogram-outlet');
    Route::post('/infogram-data-outlet', [LaporanMemberController::class, 'getData'])->name('infogram-data-outlet');

    Route::get('/home-user', [App\Http\Controllers\HomeController::class, 'indexuser'])->name('home-user');

    Route::prefix('master-data')->middleware(['role_or_permission:Maintener|master-data'])->group(function () {

        Route::get('/', [MasterDataController::class, 'index'])->name('master-data');

        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('roles');
            Route::post('/get-data', [RoleController::class, 'getData'])->name('roles.get-data');
            Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/update', [RoleController::class, 'update'])->name('roles.update');
            Route::get('/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users');
            Route::post('/get-data', [UserController::class, 'getData'])->name('users.get-data');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/store', [UserController::class, 'store'])->name('users.store');
            Route::get('/detail/{id}', [UserController::class, 'detail'])->name('users.detail');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/update', [UserController::class, 'update'])->name('users.update');
            Route::get('/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        Route::prefix('outlet')->group(function () {
            Route::get('/', [OutletController::class, 'index'])->name('outlet');
            Route::post('/get-data', [OutletController::class, 'getData'])->name('outlet.get-data');
            Route::get('/create', [OutletController::class, 'create'])->name('outlet.create');
            Route::post('/store', [OutletController::class, 'store'])->name('outlet.store');
            Route::get('/detail/{id}', [OutletController::class, 'detail'])->name('outlet.detail');
            Route::get('/edit/{id}', [OutletController::class, 'edit'])->name('outlet.edit');
            Route::put('/update', [OutletController::class, 'update'])->name('outlet.update');
            Route::get('/destroy/{id}', [OutletController::class, 'destroy'])->name('outlet.destroy');
        });

        Route::prefix('harga')->group(function () {
            Route::get('/', [HargaController::class, 'index'])->name('harga');
            Route::post('/get-data', [HargaController::class, 'getData'])->name('harga.get-data');
            Route::get('/create', [HargaController::class, 'create'])->name('harga.create');
            Route::post('/store', [HargaController::class, 'store'])->name('harga.store');
            Route::get('/detail/{id}', [HargaController::class, 'detail'])->name('harga.detail');
            Route::get('/edit/{id}', [HargaController::class, 'edit'])->name('harga.edit');
            Route::put('/update', [HargaController::class, 'update'])->name('harga.update');
            Route::get('/destroy/{id}', [HargaController::class, 'destroy'])->name('harga.destroy');
        });

        Route::prefix('layanan')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('layanan');
            Route::post('/get-data', [LayananController::class, 'getData'])->name('layanan.get-data');
            Route::get('/create', [LayananController::class, 'create'])->name('layanan.create');
            Route::post('/store', [LayananController::class, 'store'])->name('layanan.store');
            Route::get('/detail/{id}', [LayananController::class, 'detail'])->name('layanan.detail');
            Route::get('/edit/{id}', [LayananController::class, 'edit'])->name('layanan.edit');
            Route::put('/update', [LayananController::class, 'update'])->name('layanan.update');
            Route::get('/destroy/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
        });

        Route::prefix('member')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('user-member');
            Route::post('/get-data', [MemberController::class, 'getData'])->name('user-member.get-data');
            Route::get('/create', [MemberController::class, 'create'])->name('user-member.create');
            Route::post('/store', [MemberController::class, 'store'])->name('user-member.store');
            Route::get('/detail/{id}', [MemberController::class, 'detail'])->name('user-member.detail');
            Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('user-member.edit');
            Route::put('/update', [MemberController::class, 'update'])->name('user-member.update');
            Route::get('/destroy/{id}', [MemberController::class, 'destroy'])->name('user-member.destroy');
        });

        Route::prefix('parfume')->group(function () {
            Route::get('/', [ParfumeController::class, 'index'])->name('parfume');
            Route::post('/get-data', [ParfumeController::class, 'getData'])->name('parfume.get-data');
            Route::get('/create', [ParfumeController::class, 'create'])->name('parfume.create');
            Route::post('/store', [ParfumeController::class, 'store'])->name('parfume.store');
            Route::get('/detail/{id}', [ParfumeController::class, 'detail'])->name('parfume.detail');
            Route::get('/edit/{id}', [ParfumeController::class, 'edit'])->name('parfume.edit');
            Route::put('/update', [ParfumeController::class, 'update'])->name('parfume.update');
            Route::get('/destroy/{id}', [ParfumeController::class, 'destroy'])->name('parfume.destroy');
        });
        
        Route::prefix('corporate')->group(function () {
            Route::get('/', [CorporateController::class, 'index'])->name('user_corporate');
            Route::get('/getData', [CorporateController::class, 'getData'])->name('user_corporate.getData');
            Route::get('/add', [CorporateController::class, 'add'])->name('user_corporate.add');
            Route::post('/save', [CorporateController::class, 'save'])->name('user_corporate.save');
            Route::get('/detail/{id}', [CorporateController::class, 'detail'])->name('user_corporate.detail');
            Route::get('/edit/{id}', [CorporateController::class, 'edit'])->name('user_corporate.edit');
            Route::put('/update', [CorporateController::class, 'update'])->name('user_corporate.update');
            Route::get('/destroy/{id}', [CorporateController::class, 'destroy'])->name('user_corporate.destroy');
        });

    });

    Route::prefix('registrasi')->middleware(['role_or_permission:Maintener|registrasi'])->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('registrasi');
        Route::post('/get-layanan', [KasirController::class, 'getDataLayanan'])->name('registrasi.get-data-layanan');
        Route::get('/print/{kode_transaksi}', [KasirController::class, 'print'])->name('registrasi.print');
        Route::post('/store', [KasirController::class, 'store'])->name('registrasi.store');
        Route::get('/history', [KasirController::class, 'history'])->name('registrasi.history');
        Route::post('/getDataHistory', [KasirController::class, 'getDataHistory'])->name('registrasi.getDataHistory');
        Route::get('/history/{kode_transaksi}/edit', [KasirController::class, 'edit'])->name('registrasi.edit');
        Route::get('/history/{kode_transaksi}', [KasirController::class, 'detail'])->name('registrasi.detail');
        Route::get('/history/{kode_transaksi}/deleteLayanan', [KasirController::class, 'deleteLayanan'])->name('registrasi.deleteLayanan');
        Route::get('/history/{kode_transaksi}/deleteImg', [KasirController::class, 'deleteImg'])->name('registrasi.deleteImg');
        Route::post('/update', [KasirController::class, 'update'])->name('registrasi.update');
    });

    Route::prefix('request-laundry')->middleware(['role_or_permission:Maintener|registrasi'])->group(function () {
        Route::get('/', [RequestLaundryController::class, 'index'])->name('request-laundry');
        Route::post('/get-layanan', [RequestLaundryController::class, 'getDataLayanan'])->name('request-laundry.get-data-layanan');
        Route::get('/print/{kode_transaksi}', [RequestLaundryController::class, 'print'])->name('request-laundry.print');
        Route::post('/store', [RequestLaundryController::class, 'store'])->name('request-laundry.store');
        Route::post('/get-data', [RequestLaundryController::class, 'getData'])->name('request-laundry.get-data');
        Route::get('/create/{id}', [RequestLaundryController::class, 'create'])->name('request-laundry.create');
        Route::get('/history', [RequestLaundryController::class, 'history'])->name('request-laundry.history');
        Route::post('/getDataHistory', [RequestLaundryController::class, 'getDataHistory'])->name('request-laundry.getDataHistory');
        Route::get('/history/{kode_transaksi}/edit', [RequestLaundryController::class, 'edit'])->name('request-laundry.edit');
        Route::get('/history/{kode_transaksi}', [RequestLaundryController::class, 'detail'])->name('request-laundry.detail');
        Route::get('/history/{kode_transaksi}/deleteLayanan', [RequestLaundryController::class, 'deleteLayanan'])->name('request-laundry.deleteLayanan');
        Route::get('/history/{kode_transaksi}/deleteImg', [RequestLaundryController::class, 'deleteImg'])->name('request-laundry.deleteImg');
        Route::post('/update', [RequestLaundryController::class, 'update'])->name('request-laundry.update');
    });

    Route::prefix('top-up')->middleware(['role_or_permission:Maintener|topup-member'])->group(function () {
        Route::get('/', [TopupController::class, 'index'])->name('top-up');
        Route::post('/get-data', [TopupController::class, 'getData'])->name('top-up.get-data');
        Route::get('/create', [TopupController::class, 'create'])->name('top-up.create');
        Route::post('/store', [TopupController::class, 'store'])->name('top-up.store');
        Route::get('/detail/{id}', [TopupController::class, 'detail'])->name('top-up.detail');
        Route::get('/edit/{id}', [TopupController::class, 'edit'])->name('top-up.edit');
        Route::put('/update', [TopupController::class, 'update'])->name('top-up.update');
        Route::get('/destroy/{id}', [TopupController::class, 'destroy'])->name('top-up.destroy');
        Route::post('/get-data-member', [TopupController::class, 'getDataMember'])->name('top-up.get-data-member');
    });


    Route::prefix('expedisi-jadwal-jemput')->middleware(['role_or_permission:Maintener|jadwal-jemput'])->group(function () {
        Route::get('/', [ExpedisiJadwalJemputController::class, 'index'])->name('expedisi-jadwal-jemput');
        Route::post('/get-data', [ExpedisiJadwalJemputController::class, 'getData'])->name('expedisi-jadwal-jemput.get-data');
        Route::get('/create', [ExpedisiJadwalJemputController::class, 'create'])->name('expedisi-jadwal-jemput.create');
        Route::post('/store', [ExpedisiJadwalJemputController::class, 'store'])->name('expedisi-jadwal-jemput.store');
        Route::get('/detail/{id}', [ExpedisiJadwalJemputController::class, 'detail'])->name('expedisi-jadwal-jemput.detail');
        Route::get('/edit/{id}', [ExpedisiJadwalJemputController::class, 'edit'])->name('expedisi-jadwal-jemput.edit');
        Route::put('/update', [ExpedisiJadwalJemputController::class, 'update'])->name('expedisi-jadwal-jemput.update');
        Route::get('/destroy/{id}', [ExpedisiJadwalJemputController::class, 'destroy'])->name('expedisi-jadwal-jemput.destroy');
        Route::post('/get-data-user', [ExpedisiJadwalJemputController::class, 'getDataUser'])->name('expedisi-jadwal-jemput.get-data-user');
        Route::post('/get-data-info', [ExpedisiJadwalJemputController::class, 'getDataInfo'])->name('expedisi-jadwal-jemput.get-data-info');
    });

    Route::prefix('expedisi-jemput')->middleware(['role_or_permission:Maintener|jemput-barang'])->group(function () {
        Route::get('/', [ExpedisiJemputController::class, 'index'])->name('expedisi-jemput');
        Route::post('/get-data', [ExpedisiJemputController::class, 'getData'])->name('expedisi-jemput.get-data');
        Route::get('/create', [ExpedisiJemputController::class, 'create'])->name('expedisi-jemput.create');
        Route::post('/store', [ExpedisiJemputController::class, 'store'])->name('expedisi-jemput.store');
        Route::get('/detail/{id}', [ExpedisiJemputController::class, 'detail'])->name('expedisi-jemput.detail');
        Route::get('/edit/{id}', [ExpedisiJemputController::class, 'edit'])->name('expedisi-jemput.edit');
        Route::put('/update', [ExpedisiJemputController::class, 'update'])->name('expedisi-jemput.update');
        Route::get('/destroy/{id}', [ExpedisiJemputController::class, 'destroy'])->name('expedisi-jemput.destroy');
        Route::post('/get-data-permintaan', [ExpedisiJemputController::class, 'getDataPermintaan'])->name('expedisi-jemput.get-data-permintaan');
        Route::get('/edit/{id}/deleteImg', [ExpedisiJemputController::class, 'deleteImg'])->name('expedisi-jemput.edit.deleteImg');
    });

    Route::prefix('expedisi-jadwal-antar')->middleware(['role_or_permission:Maintener|jadwal-antar'])->group(function () {
        Route::get('/', [ExpedisiJadwalAntarController::class, 'index'])->name('expedisi-jadwal-antar');
        Route::post('/get-data', [ExpedisiJadwalAntarController::class, 'getData'])->name('expedisi-jadwal-antar.get-data');
        Route::get('/create', [ExpedisiJadwalAntarController::class, 'create'])->name('expedisi-jadwal-antar.create');
        Route::post('/store', [ExpedisiJadwalAntarController::class, 'store'])->name('expedisi-jadwal-antar.store');
        Route::get('/detail/{id}', [ExpedisiJadwalAntarController::class, 'detail'])->name('expedisi-jadwal-antar.detail');
        Route::get('/edit/{id}', [ExpedisiJadwalAntarController::class, 'edit'])->name('expedisi-jadwal-antar.edit');
        Route::put('/update', [ExpedisiJadwalAntarController::class, 'update'])->name('expedisi-jadwal-antar.update');
        Route::get('/destroy/{id}', [ExpedisiJadwalAntarController::class, 'destroy'])->name('expedisi-jadwal-antar.destroy');
        Route::post('/get-data-user', [ExpedisiJadwalAntarController::class, 'getDataUser'])->name('expedisi-jadwal-antar.get-data-user');
        Route::post('/get-data-info', [ExpedisiJadwalAntarController::class, 'getDataInfo'])->name('expedisi-jadwal-antar.get-data-info');
    });

    Route::prefix('expedisi-antar')->middleware(['role_or_permission:Maintener|antar-barang'])->group(function () {
        Route::get('/', [ExpedisiAntarController::class, 'index'])->name('expedisi-antar');
        Route::post('/get-data', [ExpedisiAntarController::class, 'getData'])->name('expedisi-antar.get-data');
        Route::get('/create', [ExpedisiAntarController::class, 'create'])->name('expedisi-antar.create');
        Route::post('/store', [ExpedisiAntarController::class, 'store'])->name('expedisi-antar.store');
        Route::get('/detail/{id}', [ExpedisiAntarController::class, 'detail'])->name('expedisi-antar.detail');
        Route::get('/edit/{id}', [ExpedisiAntarController::class, 'edit'])->name('expedisi-antar.edit');
        Route::put('/update', [ExpedisiAntarController::class, 'update'])->name('expedisi-antar.update');
        Route::get('/destroy/{id}', [ExpedisiAntarController::class, 'destroy'])->name('expedisi-antar.destroy');
        Route::post('/get-data-user', [ExpedisiAntarController::class, 'getDataUser'])->name('expedisi-antar.get-data-user');
        Route::post('/get-data-info', [ExpedisiAntarController::class, 'getDataInfo'])->name('expedisi-antar.get-data-info');
    });

    Route::prefix('qc')->middleware(['role_or_permission:Maintener|quality-control'])->group(function () {
        Route::get('/', [QcController::class, 'index'])->name('qc');
        Route::post('/get-data', [QcController::class, 'getData'])->name('qc.get-data');
        Route::post('/store', [QcController::class, 'store'])->name('qc.store');
        Route::get('/history', [QcController::class, 'history'])->name('qc.history');
        Route::post('/history/getDataHistory', [QcController::class, 'getDataHistory'])->name('qc.history.getDataHistory');
        Route::post('/history/restore', [QcController::class, 'restore'])->name('qc.history.restore');
    });

    Route::prefix('cuci')->middleware(['role_or_permission:Maintener|cuci'])->group(function () {
        Route::get('/', [CuciController::class, 'index'])->name('cuci');
        Route::post('/get-data', [CuciController::class, 'getData'])->name('cuci.get-data');
        Route::post('/get-request', [CuciController::class, 'getRequest'])->name('cuci.request');
        Route::post('/store', [CuciController::class, 'store'])->name('cuci.store');
    });

    Route::prefix('pengeringan')->middleware(['role_or_permission:Maintener|pengeringan'])->group(function () {
        Route::get('/', [PengeringanController::class, 'index'])->name('pengeringan');
        Route::post('/get-data', [PengeringanController::class, 'getData'])->name('pengeringan.get-data');
        Route::post('/get-request', [PengeringanController::class, 'getRequest'])->name('pengeringan.request');
        Route::post('/store', [PengeringanController::class, 'store'])->name('pengeringan.store');
    });

    Route::prefix('setrika')->middleware(['role_or_permission:Maintener|setrika'])->group(function () {
        Route::get('/', [SetrikaController::class, 'index'])->name('setrika');
        Route::post('/get-data', [SetrikaController::class, 'getData'])->name('setrika.get-data');
        Route::post('/get-request', [SetrikaController::class, 'getRequest'])->name('setrika.request');
        Route::post('/store', [SetrikaController::class, 'store'])->name('setrika.store');
    });

    // Ada Dua
    Route::prefix('expedisi-jadwal-antar')->middleware(['role_or_permission:Maintener|jadwal-antar'])->group(function () {
        Route::get('/', [ExpedisiJadwalAntarController::class, 'index'])->name('expedisi-jadwal-antar');
        Route::post('/get-data', [ExpedisiJadwalAntarController::class, 'getData'])->name('expedisi-jadwal-antar.get-data');
        Route::get('/create', [ExpedisiJadwalAntarController::class, 'create'])->name('expedisi-jadwal-antar.create');
        Route::post('/store', [ExpedisiJadwalAntarController::class, 'store'])->name('expedisi-jadwal-antar.store');
        Route::get('/detail/{id}', [ExpedisiJadwalAntarController::class, 'detail'])->name('expedisi-jadwal-antar.detail');
        Route::get('/edit/{id}', [ExpedisiJadwalAntarController::class, 'edit'])->name('expedisi-jadwal-antar.edit');
        Route::put('/update', [ExpedisiJadwalAntarController::class, 'update'])->name('expedisi-jadwal-antar.update');
        Route::get('/destroy/{id}', [ExpedisiJadwalAntarController::class, 'destroy'])->name('expedisi-jadwal-antar.destroy');
        Route::post('/get-data-user', [ExpedisiJadwalAntarController::class, 'getDataUser'])->name('expedisi-jadwal-antar.get-data-user');
    });

    //MEMBER
    Route::prefix('history-laundry')->middleware(['role_or_permission:Maintener|history-transaksi'])->group(function () {
        Route::get('/', [HistoryLaundryController::class, 'index'])->name('history-laundry');
        Route::post('/get-data', [HistoryLaundryController::class, 'getData'])->name('history-laundry.get-data');
        Route::get('/create', [HistoryLaundryController::class, 'create'])->name('history-laundry.create');
        Route::post('/store', [HistoryLaundryController::class, 'store'])->name('history-laundry.store');
        Route::get('/detail/{id}', [HistoryLaundryController::class, 'detail'])->name('history-laundry.detail');
        Route::get('/edit/{id}', [HistoryLaundryController::class, 'edit'])->name('history-laundry.edit');
        Route::put('/update', [HistoryLaundryController::class, 'update'])->name('history-laundry.update');
        Route::get('/destroy/{id}', [HistoryLaundryController::class, 'destroy'])->name('history-laundry.destroy');
        Route::post('/get-data-layanan', [HistoryLaundryController::class, 'getDataLayanan'])->name('history-laundry.get-data-layanan');
        Route::post('/get-data-parfume', [HistoryLaundryController::class, 'getDataParfume'])->name('history-laundry.get-data-parfume');
        Route::get('/like/{id}', [HistoryLaundryController::class, 'like'])->name('history-laundry.like');
        Route::get('/dislike/{id}', [HistoryLaundryController::class, 'dislike'])->name('history-laundry.dislike');
        Route::post('/get-data-info', [HistoryLaundryController::class, 'getDataInfo'])->name('history-laundry.get-data-info');
    });

    // MEMBER
    Route::prefix('permintaan-laundry')->middleware(['role_or_permission:Maintener|history-transaksi'])->group(function () {
        Route::get('/', [PermintaanLaundryController::class, 'index'])->name('permintaan-laundry');
        Route::post('/get-data', [PermintaanLaundryController::class, 'getData'])->name('permintaan-laundry.get-data');
        Route::get('/create', [PermintaanLaundryController::class, 'create'])->name('permintaan-laundry.create');
        Route::post('/store', [PermintaanLaundryController::class, 'store'])->name('permintaan-laundry.store');
        Route::get('/detail/{id}', [PermintaanLaundryController::class, 'detail'])->name('permintaan-laundry.detail');
        Route::get('/edit/{id}', [PermintaanLaundryController::class, 'edit'])->name('permintaan-laundry.edit');
        Route::put('/update', [PermintaanLaundryController::class, 'update'])->name('permintaan-laundry.update');
        Route::get('/destroy/{id}', [PermintaanLaundryController::class, 'destroy'])->name('permintaan-laundry.destroy');
        Route::post('/get-data-layanan', [PermintaanLaundryController::class, 'getDataLayanan'])->name('permintaan-laundry.get-data-layanan');
        Route::post('/get-data-parfume', [PermintaanLaundryController::class, 'getDataParfume'])->name('permintaan-laundry.get-data-parfume');
    });

    Route::prefix('jemput-pesanan')->middleware(['role_or_permission:Maintener|registrasi'])->group(function () {
        Route::get('/', [JemputPesananController::class, 'index'])->name('jemput_pesanan');
        Route::post('/get-layanan', [JemputPesananController::class, 'getDataLayanan'])->name('jemput_pesanan.get-data-layanan');
        Route::get('/print/{kode_transaksi}', [JemputPesananController::class, 'print'])->name('jemput_pesanan.print');
        Route::post('/store', [JemputPesananController::class, 'store'])->name('jemput_pesanan.store');
        Route::post('/get-data', [JemputPesananController::class, 'getData'])->name('jemput_pesanan.get-data');
        Route::get('/create/{id}', [JemputPesananController::class, 'create'])->name('jemput_pesanan.create');
        Route::get('/history', [JemputPesananController::class, 'history'])->name('jemput_pesanan.history');
        Route::post('/getDataHistory', [JemputPesananController::class, 'getDataHistory'])->name('jemput_pesanan.getDataHistory');
        Route::get('/history/{kode_transaksi}/edit', [JemputPesananController::class, 'edit'])->name('jemput_pesanan.edit');
        Route::get('/history/{kode_transaksi}', [JemputPesananController::class, 'detail'])->name('jemput_pesanan.detail');
        Route::get('/history/{kode_transaksi}/deleteLayanan', [JemputPesananController::class, 'deleteLayanan'])->name('jemput_pesanan.deleteLayanan');
        Route::get('/history/{kode_transaksi}/deleteImg', [JemputPesananController::class, 'deleteImg'])->name('jemput_pesanan.deleteImg');
        Route::post('/update', [JemputPesananController::class, 'update'])->name('jemput_pesanan.update');
    });
    
    Route::prefix('jemput-non-pesanan')->middleware(['role_or_permission:Maintener|registrasi'])->group(function () {
        Route::get('/', [JemputNonPesananController::class, 'index'])->name('jemput_non_pesanan');
        Route::post('/getDataCorporate', [JemputNonPesananController::class, 'getDataCorporate'])->name('jemput_non_pesanan.getDataCorporate');
        Route::post('/get-layanan', [JemputNonPesananController::class, 'getDataLayanan'])->name('jemput_non_pesanan.get-data-layanan');
        Route::get('/print/{kode_transaksi}', [JemputNonPesananController::class, 'print'])->name('jemput_non_pesanan.print');
        Route::post('/store', [JemputNonPesananController::class, 'store'])->name('jemput_non_pesanan.store');
        Route::get('/history', [JemputNonPesananController::class, 'history'])->name('jemput_non_pesanan.history');
        Route::post('/getDataHistory', [JemputNonPesananController::class, 'getDataHistory'])->name('jemput_non_pesanan.getDataHistory');
        Route::get('/history/{kode_transaksi}/edit', [JemputNonPesananController::class, 'edit'])->name('jemput_non_pesanan.edit');
        Route::get('/history/{kode_transaksi}', [JemputNonPesananController::class, 'detail'])->name('jemput_non_pesanan.detail');
        Route::get('/history/{kode_transaksi}/deleteLayanan', [JemputNonPesananController::class, 'deleteLayanan'])->name('jemput_non_pesanan.deleteLayanan');
        Route::get('/history/{kode_transaksi}/deleteImg', [JemputNonPesananController::class, 'deleteImg'])->name('jemput_non_pesanan.deleteImg');
        Route::post('/update', [JemputNonPesananController::class, 'update'])->name('jemput_non_pesanan.update');
    });

    Route::prefix('laporan-member')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanMemberController::class, 'index'])->name('laporan-member');
        Route::post('/get-data', [LaporanMemberController::class, 'getData'])->name('laporan-member.get-data');
    });

    Route::prefix('laporan-outlet')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanOutletController::class, 'index'])->name('laporan-outlet');
        Route::post('/get-data', [LaporanOutletController::class, 'getData'])->name('laporan-outlet.get-data');
    });

    Route::prefix('laporan-expedisi')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanExpedisiController::class, 'index'])->name('laporan-expedisi');
        Route::post('/get-data', [LaporanExpedisiController::class, 'getData'])->name('laporan-expedisi.get-data');
    });

    Route::prefix('laporan-absen')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanAbsenController::class, 'index'])->name('laporan-absen');
        Route::post('/get-data', [LaporanAbsenController::class, 'getData'])->name('laporan-absen.get-data');
    });

    Route::prefix('laporan-agen')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanAgenController::class, 'index'])->name('laporan-agen');
        Route::post('/get-data', [LaporanAgenController::class, 'getData'])->name('laporan-agen.get-data');
    });

    Route::prefix('laporan-frenchaise')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanFrenchaiseController::class, 'index'])->name('laporan-frenchaise');
        Route::post('/get-data', [LaporanFrenchaiseController::class, 'getData'])->name('laporan-frenchaise.get-data');
    });
    
    Route::prefix('infogram-expedisi')->middleware(['role_or_permission:Maintener|infogram'])->group(function () {
        Route::get('/', [InfogramExpedisiController::class, 'index'])->name('infogram-expedisi');
        Route::post('/get-data', [InfogramExpedisiController::class, 'getData'])->name('infogram-expedisi.get-data');
    });

    Route::prefix('infogram-outlet')->middleware(['role_or_permission:Maintener|infogram'])->group(function () {
        Route::get('/', [InfogramOutletController::class, 'index'])->name('infogram-outlet');
        Route::post('/get-data', [InfogramOutletController::class, 'getData'])->name('infogram-outlet.get-data');
    });

    Route::prefix('rekap-complain')->middleware(['role_or_permission:Maintener|rekap-complain'])->group(function () {
        Route::get('/', [RekapComplainController::class, 'index'])->name('rekap-complain');
        Route::post('/get-data', [RekapComplainController::class, 'getData'])->name('rekap-complain.get-data');
        Route::get('/detail/{id}', [RekapComplainController::class, 'detail'])->name('rekap-complain.detail');
        Route::post('/get-data-info', [RekapComplainController::class, 'getDataInfo'])->name('rekap-complain.get-data-info');
    });


    Route::prefix('laporan/corporate')->middleware(['role_or_permission:Maintener|laporan'])->group(function () {
        Route::get('/', [LaporanCorporateController::class, 'index'])->name('laporan.corporate');
        Route::post('/getData', [LaporanCorporateController::class, 'getData'])->name('laporan.corporate.getData');
        Route::get('/detail/{id}', [LaporanCorporateController::class, 'detail'])->name('laporan.corporate.detail');
        Route::get('/exportExcel', [LaporanCorporateController::class, 'exportExcel'])->name('laporan.corporate.exportExcel');
        Route::get('/exportPdf', [LaporanCorporateController::class, 'exportPdf'])->name('laporan.corporate.exportPdf');
    });

});

