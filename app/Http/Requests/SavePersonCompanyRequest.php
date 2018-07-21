<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\PersonCompany;
use Auth;
use App\User;

class SavePersonCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::guest() && (Auth::user()->type === User::ADMINISTRATION || Auth::user()->type === User::ROOT);;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return PersonCompany::getValidationRules();
    }
}
