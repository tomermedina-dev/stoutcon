<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;



class BalanceSheetValidation extends FormRequest
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
            'account' => 'required',
            'type' => 'required',
            // 'category' => 'required',
            'status' => 'required',
            'amount' => 'required', 
        ];
    }

     public function messages(){
      return [
          //
      ];
    }
}
