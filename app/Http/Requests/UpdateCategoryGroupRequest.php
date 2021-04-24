<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\CategoryGroup;
use Illuminate\Validation\Rule;

class UpdateCategoryGroupRequest extends HiddenIdRequest
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
        $rules = CategoryGroup::$rules;

        $rules['name'] = [
            'required',
            Rule::unique('category_groups')
                ->ignore($this->slug, 'slug')
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Duplicate record - this category group name has already been taken.',
        ];
    }
}
