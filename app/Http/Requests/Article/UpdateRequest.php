<?php

namespace App\Http\Requests\Article;

use App\Rules\ReachableURL;
use App\Rules\ValidImageURL;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'section' => 'nullable|exists:sections,id',
            'headline' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'thumbnail' => [
                'nullable',
                'url',
                new ReachableURL,
                new ValidImageURL
            ]
        ];
    }
}
