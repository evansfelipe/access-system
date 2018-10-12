<?php namespace App;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name'];
    
    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'name' => ['max' => 50]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:'.Activity::LENGTHS['name']['max'],
                'unique:activities'
            ]
        ];
    }

    public function personCompany()
    {
        return $this->hasMany('App\PersonCompany');
    }

    /**
     * Subactivities associated with this activity.
     */
    public function subactivities()
    {
        return $this->hasMany('App\Subactivity');
    }

    public function toListArray()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
        ];
    }
}
