<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
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
            'name' => 'required|min:2|max:50',      

            'email' => 'required|email|unique:users,email,'.$this->route('user'),

            'username' => 'required|unique:users,username,'.$this->route('user'),

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
