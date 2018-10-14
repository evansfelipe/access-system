<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'gate_id', 'start', 'end', 'company_id'];
    
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
            'company_id' => [
                'nullable',
                'integer',
                'exists:companies,id'
            ],
            'name' => [
                'nullable',
                'string',
                'max:'.Group::LENGTHS['name']['max'],
            ],
            'gate_id' => [
                'required',
                'integer',
                'exists:gates,id'
            ],
            'start' => [
                'required',
                "regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", 
            ],
            'end' => [
                'required',
                "regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", 
            ],
            'days' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                    $keys = array_keys($value);
                    foreach ($keys as $key) {
                        if (!in_array($key, $days)) {
                            $fail('Algún día es inválido');
                        }
                    }
                },
            ],
            'days.*' => [
                'boolean'
            ]
        ];
    }

    public static function getValidationRulesForCompany()
    {
        $rules = Group::getValidationRules();
        $new_rules = [
            'groups' => [
                'required',
                'array'
            ]
        ];
        foreach ($rules as $key => $value) {
            $new_rules['groups.*.'.$key] = $value;
        }
        return $new_rules;
    }

    /**
     * The company that this groups belongs to. It may be null if the group doesn't belongs to any company.
     */
    public function company()
    {
        return $this->belongsTo('\App\Company')->select(['id', 'name']);
    }

    /**
     * The gate that this group manages.
     */
    public function gate()
    {
        return $this->belongsTo('\App\Gate')->select(['id', 'name']);
    }

    public function jobs()
    {
        return $this->belongsToMany('App\PersonCompany','person_job_groups','group_id','job_id');
    }

    public function rangeToString()
    {
        $start_hour = date('H:i', strtotime($this->start));
        $end_hour   = date('H:i', strtotime($this->end));
        return $start_hour.' - '.$end_hour;
    }

    public function formatedName()
    {
        $ret = $this->name ?? '';;
        if($ret === '') {
            $company_name   = $this->company ? $this->company->name.' -' : '';
            // Composes the name
            $ret = $company_name.' '.$this->gate->name.' ('.$this->rangeToString().')';
        }
        return $ret;
    }

    /**
     * Gets the ordinal number of the char stored and transforms it to a seven bits string.
     * Then the string is completed with zeros to the left.
     * Finally the string is converted to an array and returns it.
     */
    public function daysToArray()
    {
        $bin = decbin(ord($this->days));
        $bin = str_pad($bin, 7, 0, STR_PAD_LEFT);
        $bin = str_split($bin);
        return $bin;
    }

    /**
     * Opposite of the previous function.
     * Receives an array with the ones and zeros of the days and transforms it to an array.
     * Put the ones and zeros all together in a string and gets the decimal number of the binary.
     * Finally converts the ordinal to a char and stores it in the days variable.
     */
    public function daysToChar($days_array)
    {
        $days = array_map(function($day) { return $day === true ? 1 : 0; }, $days_array);
        $this->days = chr(bindec(implode("", $days)));
    }

    public function toShowArray()
    {
        $days_array = $this->daysToArray();
        return [
            'id'            => $this->id,
            'name'          => $this->formatedName(),
            'gate'          => $this->gate->name,
            'range'         => $this->rangeToString().($this->end < $this->start ? ' (+1d)' : ''),
            'company'       => $this->company ? $this->company->name : '-',
            'company_id'    => $this->company ? $this->company->id : null,
            'days'          => [
                'Lunes'     => $days_array[0],
                'Martes'    => $days_array[1],
                'Miércoles' => $days_array[2],
                'Jueves'    => $days_array[3],
                'Viernes'   => $days_array[4],
                'Sábado'    => $days_array[5],
                'Domingo'   => $days_array[6]
            ]
        ];
    }

    public function toListArray()
    {
        return [
            'id'            => $this->id,
            'name'          => $this->formatedName(),
            'gate'          => $this->gate->name,
            'range'         => $this->rangeToString().($this->end < $this->start ? ' (+1d)' : ''),
            'company'       => $this->company ? $this->company->name : '-'
        ];
    }

    public function deleteCascade()
    {
        $this->jobs()->detach();
        $this->delete();
    }
}
