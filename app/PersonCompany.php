<?php namespace App;

use App\Card;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonCompany extends Pivot
{
    protected $table = 'company_people';
    protected $fillable = ['company_id', 'activity_id', 'subactivities', 'art_company', 'art_number', 'groups'];

    public const LENGTHS = [
        'art_company' => ['max' => 50],
        'art_number'  => ['max' => 50],
    ];

    public static function getValidationRules()
    {
        return [
            'jobs' => [
                'required',
                'array'
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
            'jobs.*.groups' => [
                'array',
            ],
            'jobs.*.groups.*' => [
                'integer',
                'exists:groups,id'
            ],
            'jobs.*.art_company' => [
                'required',
                'string'
            ],
            'jobs.*.art_number' => [
                'required',
                'numeric',
            ],
            'jobs.*.cards' => [
                'required',
                'array'
            ],
            'jobs.*.cards.*.number' => Card::getValidationRules()['number'],
            'jobs.*.cards.*.from'   => [
                'required',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after_or_equal:'.date('Y-m-d'), 
            ],
            'jobs.*.cards.*.until'  => [
                'required',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
            ],
        ];
    }

    public function person()
    {
        return $this->hasOne('App\Person', 'id', 'person_id');
    }

    public function company()
    {
        return $this->hasOne('App\Company', 'id', 'company_id');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function cards()
    {
        return $this->hasMany('App\Card', 'person_company_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'person_job_groups', 'job_id', 'group_id');
    }

    public function company_note()
    {
        return $this->hasOne('App\PersonDocument','id','company_note_id');
    }

    public function art_file()
    {
        return $this->hasOne('App\PersonDocument', 'id', 'art_file_id');
    }
}
