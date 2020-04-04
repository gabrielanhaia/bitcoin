<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateTransactionRequest
 * @package App\Http\Requests\V1
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class CreateTransactionRequest extends FormRequest
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
            'address' => 'required|string',
            'address_destination' => 'required|string',
            'total_transaction' => 'required|numeric'
        ];
    }
}
