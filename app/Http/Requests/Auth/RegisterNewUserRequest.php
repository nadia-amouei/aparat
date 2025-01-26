<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterNewUserRequest extends FormRequest
{
    use GetRegisterFieldValueTrait;
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
        //TODO: add mobile validation
        return [
            'mobile'=>['required_without:email', new MobileRule], //, new MobileRule
            'email'=>'required_without:mobile|email'
        ];
    }


}
