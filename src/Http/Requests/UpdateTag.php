<?php

namespace Biigle\Modules\AnnotationTags\Http\Requests;

use Biigle\Modules\AnnotationTags\Tag;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTag extends FormRequest
{
    /**
     * The tag to update.
     *
     * @var Tag
     */
    public $tag;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->tag = Tag::findOrFail($this->route('id'));
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
            'name' => 'filled|min:2|max:512',
            'color' => 'filled|string|regex:/^\#?[A-Fa-f0-9]{6}$/',
        ];
    }
}
