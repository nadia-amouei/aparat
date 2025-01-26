<?php

namespace App\Http\Requests\channel;

use App\Models\User;
use App\Rules\ChannelNameRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route()->hasParameter('id') && auth()->user()->type != User::TYPES_ADMIN){
            return false;
        }
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
            'name'=> ['required' , new ChannelNameRule() ],
            'website'=>'url|max:255|nullable',
            'info'=>'string|nullable'
        ];
    }
}
