<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $min_age = getSettingsOf('min_age');
        return [
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'dob' => "required|date|before:-$min_age years",
            "phone" => "required|phone:AUTO|unique:customers",
            'address' => "required"
        ];
    }
}
