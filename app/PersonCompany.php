<?php namespace App;

use App\Card;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonCompany extends Pivot
{
    protected $table = 'company_people';
    protected $fillable = ['company_id', 'activity_id'];


    public const LENGTHS = [
    ];

    public static function getValidationRules()
    {
        return [
            'jobs' => [
                'required',
                'array',
                'min:1'
            ],
            'jobs.*.company_id' => [
                'nullable',
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
            ],
            'jobs.*.cards' => [
                'required',
                'array',
                'min:1'
            ],
            'jobs.*.cards.*.number' => Card::getValidationRules()['number'],
            'jobs.*.cards.*.from' => Card::getValidationRules()['from'],
            'jobs.*.cards.*.until' => Card::getValidationRules()['until'],
        ];
    }

    public function activity()
    {
        return $this->hasOne('App\Activity');
    }

    public function cards()
    {
        return $this->hasMany('App\Card', 'person_company_id');
    }
}
