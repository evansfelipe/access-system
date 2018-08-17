<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonCompany extends Pivot
{
    protected $table = 'company_people';
    protected $fillable = ['company_id','activity_id','art','pbip'];


    public const LENGTHS = [
        'art' => ['max' => 50]
    ];

    public static function getValidationRules()
    {
        return [
            'art' => [
                'required',
                'string',
                'max:'.PersonCompany::LENGTHS['art']['max']
            ],
            'pbip' => [
                'nullable',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('Y-m-d'), 
            ],
            'jobs' => [
                'required',
                'array',
                'min:1'
            ],
            'jobs.*.company_id' => [
                'integer',
                'exists:companies,id'
            ],
            'jobs.*.activity_id' => [
                'required',
                'integer',
                'exists:activities,id'
            ],
            'jobs.*.subactivities' => [
                'array'
            ],
            'jobs.*.subactivities.*' => [
                'string'
            ]
        ];
    }

    public function activity()
    {
        return $this->hasOne('App\Activity');
    }
}
