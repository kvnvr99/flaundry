<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Corporate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'corporate';

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
