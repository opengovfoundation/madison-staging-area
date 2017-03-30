<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'user_meta';

    protected $fillable = array('user_id', 'meta_key');

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
