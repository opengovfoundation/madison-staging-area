<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    protected $fillable = ['token', 'expires_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
