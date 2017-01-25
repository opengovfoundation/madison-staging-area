<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\AdminRequest;

class Update extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'string|required',
            'nav_title' => 'string|required',
            'page_title' => 'string',
            'header' => 'string',
            'header_nav_link' => 'boolean|required',
            'footer_nav_link' => 'boolean|required',
            'external' => 'boolean|required',
            'page_content' => 'string',
        ];
    }

}
