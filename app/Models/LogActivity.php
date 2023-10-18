<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'modul',
        'model',
        'action',
        'note',
        'old_data',
        'new_data',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
