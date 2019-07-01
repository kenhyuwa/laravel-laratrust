<?php

namespace App\Http\Requests\Access\Roles;

use App\Models\Role;
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
            if(auth()->user()->canIndexRoles() || auth()->user()->canCreateRoles() || auth()->user()->canEditRoles()) return true;
            return false;
        }elseif($this->isMethod('post')){
            if(auth()->user()->canStoreRoles()) return true;
            return false;
        }elseif($this->isMethod('put') || $this->isMethod('patch')){
            if(auth()->user()->canUpdateRoles()) return true;
            return false;
        }elseif($this->isMethod('delete')){
            if(auth()->user()->canDestroyRoles()) return true;
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
                'name' => "required|string|max:255|unique:roles,name,{$this->role}",
            ];
        }
        else if($this->isMethod('put') || $this->isMethod('patch')){
            return [
                'name' => "required|string|max:255|unique:roles,name,{$this->role}",
            ];
        }
        else{
            return [];
        }
    }
}
