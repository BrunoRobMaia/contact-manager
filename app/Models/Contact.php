<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'observations',
    ];
    protected $dates = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
