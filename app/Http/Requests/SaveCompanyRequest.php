<?php

namespace App\Http\Requests;
use Auth;
use App\{ User, Company, Residency, Group };
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
        $company_rules = Company::getValidationRules();
        $residency_rules = Residency::getValidationRules();
        $groups_rules = Group::getValidationRulesForCompany();

        if($this->route()->getName() === 'companies.update') {
            if($this->cuit === $this->company->cuit && ($key = array_search('unique:companies', $company_rules['cuit'])) !== false) {
                unset($company_rules['cuit'][$key]);
            }
        }

        array_push($residency_rules['street'],    'required');
        array_push($residency_rules['cp'],        'required');
        array_push($residency_rules['country'],   'required');
        array_push($residency_rules['province'],  'required');
        array_push($residency_rules['city'],      'required');

        return array_merge(
            $company_rules,
            $residency_rules,
            $groups_rules
        );
    }
}
