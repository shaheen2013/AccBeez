<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
        $rules = [
            'date' => 'required',
            'description' => 'string',
            'items.0' => 'required|min:1',
            'items.*.sku' =>  'required|string',
            'items.*.rate' =>  'required|numeric',
            'items.*.quantity' =>  'required|numeric',
        ];
        return $rules;
    }
    

    public function messages()
    {
        $messages = [];
        $messages['items.0.required'] = 'At least 1 item should be added';
        foreach($this->input('items') as $key => $val)
        {
            $k = $key+1;
            $messages['items.'.$key.'.sku.required'] = 'SKU for item '.$k.' is required';
            $messages['items.'.$key.'.rate.required'] = 'Rate for item '.$k.' is required';
            $messages['items.'.$key.'.quantity.required'] = 'Quantity for item '.$k.' is required';

            $messages['items.'.$key.'.sku.string'] = 'SKU for item '.$k.' must be string';
            $messages['items.'.$key.'.rate.numeric'] = 'Rate for item '.$k.' must be number';
            $messages['items.'.$key.'.quantity.numeric'] = 'Quantity for item '.$k.' must be number';
        }
        return $messages;
    }
}
