<?php

namespace App\Http\Requests\Setting\FeaturedDocuments;

use App\Http\Requests\AdminRequest;

class Update extends AdminRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'action' => 'required|in:up,down,remove',
        ];
    }
}
