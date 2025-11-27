<?php

namespace Biigle\Modules\AnnotationTags\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagGroup extends FormRequest
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

    public function rules() 
    {
        return [
            'name' => 'required|string|max:255',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id'
        ];
    }
}