<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;



class UserDataValidation extends FormRequest
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
            //'email' => 'unique:users,email',
            'accountname' => 'unique:users,username',
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

            // 'email' => 'required|email|unique:users',

            // 'username' => 'required|unique:users',

            // 'password' => 'required|min:6',                

           // 'confirm_password' => 'required|min:6|max:20|same:password',
        ];
    }

     public function messages(){
      return [
          //
      ];
    }

}
