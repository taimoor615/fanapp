<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FancamRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ];

        // For create request
        if ($this->isMethod('post')) {
            $rules['images'] = 'required|array|max:5';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['titles'] = 'nullable|array';
            $rules['titles.*'] = 'nullable|string|max:255';
            $rules['descriptions'] = 'nullable|array';
            $rules['descriptions.*'] = 'nullable|string|max:500';
        }

        // For update request
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Please select at least one image to upload.',
            'images.max' => 'You can upload maximum 5 images at once.',
            'images.*.image' => 'All uploaded files must be images.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, JPG, or GIF format.',
            'images.*.max' => 'Each image must be less than 2MB.',
            'game_id.required' => 'Please select a game.',
            'game_id.exists' => 'Selected game is invalid.',
            'team_id.required' => 'Please select a team.',
            'team_id.exists' => 'Selected team is invalid.',
            'title.max' => 'Title cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'images.*' => 'image',
            'titles.*' => 'title',
            'descriptions.*' => 'description',
        ];
    }
}
