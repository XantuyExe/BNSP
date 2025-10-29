<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->is_admin ?? false; }
    public function rules(): array {
        return [
            'category_id' => ['required','exists:categories,id'],
            'code' => ['required','string','max:100','unique:units,code'],
            'name' => ['required','string','max:150'],
            'vintage' => ['required','in:60s,70s,80s,90s'],
            'sale_price' => ['required','integer','min:0'],
            'rent_price_5d' => ['required','integer','min:0'],
            'is_available' => ['boolean'],
            'is_sold' => ['boolean'],
            'images' => ['nullable','array'],
            'description' => ['nullable','string'],
        ];
    }
}
