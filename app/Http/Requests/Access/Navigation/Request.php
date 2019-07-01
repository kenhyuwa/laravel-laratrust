<?php

namespace App\Http\Requests\Access\Navigation;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->isMethod('get')){
            if(auth()->user()->canIndexAccess()) return true;
            return false;
        }elseif($this->isMethod('post')){
            if(auth()->user()->canStoreAccess()) return true;
            return false;
        }elseif($this->isMethod('put') || $this->isMethod('patch')){
            if(auth()->user()->canStoreAccess()) return true;
            return false;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
