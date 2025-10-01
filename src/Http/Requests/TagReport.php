<?php

namespace Biigle\Modules\AnnotationTags\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagReport extends FormRequest
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
            'file' => [
                'required',
                'file',
                'max:50000000',
                'mimetypes:text/plain,application/json',
            ],
        ];
    }
}
