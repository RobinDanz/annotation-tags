<?php

namespace Biigle\Modules\AnnotationTags\Http\Requests;

use Biigle\Modules\AnnotationTags\TagGroup;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTagGroup extends FormRequest
{
    /**
     * The TagGroup to update.
     * 
     * @var TagGroup
     */
    public $tagGroup;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->tagGroup = TagGroup::findOrFail($this->route('id'));

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