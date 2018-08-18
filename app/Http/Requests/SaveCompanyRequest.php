<?php

namespace App\Http\Requests;
use Auth;
use App\User;
use App\Company;
use App\Residency;
use Illuminate\Foundation\Http\FormRequest;

class SaveCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::guest() && (Auth::user()->type === User::ADMINISTRATION || Auth::user()->type === User::ROOT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Debugbar::info($this);
        $residency_rules = Residency::getValidationRules();
        array_push($residency_rules['street'],    'required');
        array_push($residency_rules['cp'],        'required');
        array_push($residency_rules['country'],   'required');
        array_push($residency_rules['province'],  'required');
        array_push($residency_rules['city'],      'required');
        return array_merge(
            Company::getValidationRules(),
            $residency_rules
        );
    }
}
