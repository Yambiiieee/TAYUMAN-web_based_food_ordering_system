<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:2',],
            'type' => ['required', 'min:2', 'max:10'],
            'parent' => ['required'],
            // 'doc_left' => ['required'],
            // 'doc_right' => ['required'],
        ];
    }
}
