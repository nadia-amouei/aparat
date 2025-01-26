<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class LikeVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        dd($this->video);
        return Gate::forUser(auth('api')->user())->
                allows('like',$this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'like'=>'required|boolean'
        ];
    }
}
