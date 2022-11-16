<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransferRequest extends FormRequest
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
        $min_transfer_amount = (int)getSettingsOf('min_transfer_amount');
        $max_transfer_amount = (int)getSettingsOf('max_transfer_amount');
        return [
            'account_number' => 'required|digits:11',
            'amount' => "required|numeric|min:$min_transfer_amount|max:$max_transfer_amount",
            'reason' => "nullable|max:255"
        ];
    }
}
