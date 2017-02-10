<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AdminRequest;

class PostAdmin extends AdminRequest
{
    public function rules()
    {
        return [
            'admin' => 'required|boolean',
        ];
    }
}
