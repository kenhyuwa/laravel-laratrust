<?php

namespace App\Http\Requests\User;

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
            if(auth()->user()->canIndexUsers() || auth()->user()->canCreateUsers() || auth()->user()->canEditUsers()) return true;
            return false;
        }elseif($this->isMethod('post')){
            if(auth()->user()->canStoreUsers()) return true;
            return false;
        }elseif($this->isMethod('put') || $this->isMethod('patch')){
            if(auth()->user()->canUpdateUsers()) return true;
            return false;
        }elseif($this->isMethod('delete')){
            if(auth()->user()->canDestroyUsers()) return true;
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
        if($this->isMethod('post')){
            return [
                'name' => "required|string|max:255",
                'email' => "required|string|email|max:255|unique:users,email,{$this->users}",
                'roles' => 'required'
            ];
        }
        else if($this->isMethod('put') || $this->isMethod('patch')){
            return [
                'name' => "string|max:255",
                'email' => "string|email|max:255|unique:users,email,{$this->users}",
                'roles' => 'required'
            ];
        }
        else{
            return [];
        }
    }
}
