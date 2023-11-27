<?php

namespace App\Models;

use App\Models\Outlet;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Transaksi extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_transaksi',
        'permintaan_laundry_id',
        'kasir_id',
        'member_id',
        'qc_id',
        'cuci_id',
        'pengeringan_id',
        'outlet_id',
        'nama',
        'alamat',
        'parfume',
        'no_handphone',
        'total',
        'bayar',
        'pembayaran',
        'note',
        'status',
        'is_done',
        'quantity_qc',
        'kg_qc',
        'quantity_cuci',
        'kg_cuci',
        'quantity_pengeringan',
        'kg_pengeringan',
        'corporate_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    public function TransaksiDetail() {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id', 'id');
    }

    public function outlet() {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }
}
