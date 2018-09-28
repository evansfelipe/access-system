<?php namespace App;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $fillable = [
        'person_id',
        'user_id',
        'text',
    ];

    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function toArray()
    {
        return [
            'date' => \Helpers::timestampToDate($this->created_at),
            'user' => $this->user->name,
            'text' => $this->text
        ];
    }
}