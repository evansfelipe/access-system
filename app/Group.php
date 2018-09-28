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
            'company_id' => [
                'nullable',
                'integer',
                'exists:companies,id'
            ]
        ];
    }

    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    public function gate()
    {
        return $this->belongsTo('\App\Gate');
    }

    public function toShowArray()
    {
        $name = $this->name ?? (
                ($this->company ? $this->company->name.' - ' : '') .
                ($this->gate ? $this->gate->name.' ' : '') .
                ('('.date('H:i', strtotime($this->start)) . ' - ' . date('H:i', strtotime($this->end)).')')
        );
        return [
            'id'        => $this->id,
            'name'      => $name,
            'company'   => $this->company ? $this->company->name : '-',
            'gate'      => $this->gate->name,
            'start'     => date('H:i', strtotime($this->start)),
            'end'       => date('H:i', strtotime($this->end))
        ];
    }

    public function toListArray()
    {
        $name = $this->name ?? (
            ($this->company ? $this->company->name.' - ' : '') .
            ($this->gate ? $this->gate->name.' ' : '') .
            ('('.date('H:i', strtotime($this->start)) . ' - ' . date('H:i', strtotime($this->end)).')')
        );
        return [
            'id'        => $this->id,
            'name'      => $name,
            'company_id'=> $this->company ? $this->company->id : null,
            'company'   => $this->company ? $this->company->name : '-',
            'gate'      => $this->gate->name,
            'range'     => date('H:i', strtotime($this->start)) . ' - ' . date('H:i', strtotime($this->end)) . ($this->end < $this->start ? ' (+1d)' : '')
        ];
    }
}
