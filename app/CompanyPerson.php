<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyPerson extends Pivot
{
    protected $table = 'company_people';

    public const LENGTHS = [
        'art' => ['max' => 50]
    ];

    public static function getValidationRules()
    {
        return [
            'person_id' => [
                'required',
                'exists:people,id'
            ],
            'company_id' => [
                'required',
                'exists:companies,id'
            ],
            'activity_id' => [
                'required',
                'exists:activities,id'
            ],
            'art' => [
                'string',
                'max:'.CompanyPerson::LENGTHS['art']['max']
            ],
            'pbip' => [
                'nullable',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('Y-m-d'), 
            ],
        ];
    }

    public function activity()
    {
        return $this->hasOne('App\Activity');
    }

    
}
