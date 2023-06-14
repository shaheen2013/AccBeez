<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BomRequest extends FormRequest
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
            'name' => 'required',
            'items.0' => 'required|min:1',
            'items.*.sku' =>  'required|string',
            'items.*.quantity' =>  'required|numeric',
        ];
    }
    
    public function messages()
    {
        $messages = [];
        $messages['items.0.required'] = 'At least 1 item should be added';        
        foreach($this->input('items') as $key => $val)
        {
            $k = $key+1;
            $messages['items.'.$key.'.sku.required'] = 'SKU for item '.$k.' is required';
            $messages['items.'.$key.'.quantity.required'] = 'Quantity for item '.$k.' is required';
            $messages['items.'.$key.'.sku.string'] = 'SKU for item '.$k.' must be string';
            $messages['items.'.$key.'.quantity.numeric'] = 'Quantity for item '.$k.' must be number';
        }
        return $messages;
    }
}
