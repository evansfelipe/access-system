<?php namespace App;

use App\Http\Traits\Helpers;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['business_name', 'name', 'area', 'cuit', 'expiration'];

    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'business_name' => ['max' => 50],
        'name' => ['max' => 50],
        'area' => ['max' => 50],
        'cuit' => ['max' => 15],
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'business_name' => [
                'required',
                'string',
                'max:'.Company::LENGTHS['business_name']['max']
            ],
            'name' => [
                'required',
                'string',
                'max:'.Company::LENGTHS['name']['max'],
            ],
            'area' => [
                'required',
                'string',
                'max:'.Company::LENGTHS['area']['max'],
            ],
            'cuit' => array_merge(
                [
                    'required',
                    'unique:companies',
                ], 
                Helpers::getCuilRules()
            ),
            'expiration' => [
                'required',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('Y-m-d'), 
            ],
            'phone' => [
                'required',
                'string',
            ],
            'fax' => [
                'nullable',            
                'string',
            ],
            'email' => [
                'required',
                'string',
            ],
            'web' => [
                'nullable',
                'string',
            ]                                                                       
        ];
    }

    /**
     * Decodes and returns the json where the contact information of this person 
     * is stored.
     */
    public function contactToObject()
    {
        return json_decode($this->contact);
    }

    /**
     * Gets each person associated with this company.
     */
    public function people()
    {
        return $this->belongsToMany('App\Person', 'company_people')->using('App\PersonCompany')->withPivot('activity_id','art','pbip');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Vehicle');
    }

}
