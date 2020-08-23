<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateDataValidation extends FormRequest
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
 
            //'email' => 'unique:users,email,'.$this->route('account'),
            'username' => 'unique:users,username,'.$this->route('account'),
            'name' => 'required|min:2|max:50', 
            'position' => 'required',     
            'status' => 'required', 
            'rate_per_hour' => 'required', 
            'night_differencial' => 'required', 
            // 'project' => 'required',
            'location' => 'required',
            //'period' => 'required',
            'rate_per_hour' => 'required|numeric|min:0|not_in:0',
            'night_differencial' =>  'required|numeric|min:0|not_in:0',

           // 'password' => 'min:6',                

           // 'confirm_password' => 'min:6|max:20|same:password',
        ];
    }

     public function messages(){
      return [
          //
      ];
    }

}
