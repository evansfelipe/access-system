<?php namespace App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\{ Card, Residency, Activity, Vehicle, Group, PersonDocument, PersonJobGroup };

class Person extends Model
{ 
    protected $fillable = [
        'name', 'last_name', 
        'document_type', 'document_number',
        'cuil', 
        'birthday',
        'sex',
        'blood_type',
        'homeland',
        'risk',
        'register_number',
        'pna'
    ];

    public const DNI = 0;
    public const PASSPORT = 1;

    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'last_name' => ['max' => 50],
        'name' => ['max' => 50],
        'document_number' => ['min' => 7, 'max' => 12],
        'cuil' => ['max' => 15],
        'pna' => ['min' => 10, 'max' => 15],
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'picture' => [
                'required',
                'base64file:jpeg,jpg,png'
                // 'image', 
                // 'mimes:jpeg,jpg,png'
            ],
            'last_name' => [
                'required',
                'string',
                'max:'.Person::LENGTHS['last_name']['max']
            ],
            'name' => [
                'required',
                'string', 
                'max:'.Person::LENGTHS['name']['max']
            ],
            'document_type' => [
                'required',
                'integer', 
                'in:'.Person::DNI.','.Person::PASSPORT
            ],
            'document_number' => [
                'required',
                'string', 
                'min:'.Person::LENGTHS['document_number']['min'], 
                'max:'.Person::LENGTHS['document_number']['max']
            ],
            'cuil' => array_merge(
                [
                    'unique:people',
                    'nullable'
                ], 
                \Helpers::getCuilRules()
            ),
            'birthday' => [
                'string', 
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('1900-01-01'), 
                'before:'.date('Y-m-d'), 
                'nullable'
            ],
            'sex' => [
                'string', 
                'in:F,M,O', 
                'nullable'
            ],
            'blood_type' => [
                'string', 
                'in:0-,0+,A-,A+,B-,B+,AB-,AB+', 
                'nullable'
            ],
            'homeland' => [
                'nullable',
                'string'
            ],
            'risk' => [
                'required',
                'integer',
            ],
            'register_number' => [
                'nullable',
                'integer'
            ],
            'pna' => [
                'string', 
                'min:'.Person::LENGTHS['pna']['min'], 
                'max:'.Person::LENGTHS['pna']['max'],
                'nullable'
            ],
            'email' => [
                'email',
                'nullable'
            ],
            'home_phone' => [
                'string',
                'nullable'
            ],
            'mobile_phone' => [
                'string',
                'nullable'
            ],
            'fax' => [
                'string',
                'nullable'
            ],
        ];
    }

    /**
     * Gets each company associated with this person.
     */
    public function companies()
    {
        return $this->belongsToMany('App\Company', 'company_people', 'person_id', 'company_id')
                    ->using('App\PersonCompany')
                    ->withPivot('id','activity_id','subactivities');
    }

    public function jobs()
    {
        return \App\PersonCompany::hydrate(
                        DB::table('company_people')
                        ->where('person_id', $this->id)
                        ->leftJoin('companies', 'company_people.company_id', '=', 'companies.id')
                        ->join('activities', 'company_people.activity_id', '=', 'activities.id')
                        ->select(
                            'company_people.id              as id',
                            'company_people.activity_id     as activity_id',
                            'company_people.art_company     as art_company',
                            'company_people.art_number      as art_number',
                            'company_people.subactivities   as subactivities',
                            'companies.id                   as company_id',
                            'companies.business_name        as company_name',
                            'companies.area                 as company_area',
                            'companies.cuit                 as company_cuit',
                            'companies.expiration           as company_expiration',
                            'activities.name                as activity_name',
                            'company_people.company_note_id as company_note_id',
                            'company_people.art_file_id     as art_file_id'
                        )
                        ->get()->toArray()
                )
                ->map(function($job) {
                    $job->activity_name = $job->activity->name;
                    $job->company_name  = $job->company_name ?? 'Personal';
                    $job->subactivities = json_decode($job->subactivities);
                    $job->cards         = $job->cards()->select('id', 'number', 'from', 'until', 'active')->get();
                    $job->groups        = $job->groups()->get()->map(function($group) {
                                            return [
                                                'id'    => $group->id,
                                                'name'  => $group->formatedName()
                                            ];
                                        });
                    return $job;
                });
    }

    /**
     * Gets the residency associated with this person.
     */
    public function residency()
    {
        return $this->belongsTo('App\Residency');
    }

    public function vehicles()
    {
        return $this->belongsToMany('\App\Vehicle', 'person_vehicle')->using('\App\PersonVehicle');
    }

    public function observations()
    {
        return $this->hasMany('App\Observation')->orderBy('created_at', 'desc');
    }

    public function documents()
    {
        return $this->hasMany('App\PersonDocument');
    }

    /**
     * Creates and returns a string with the last name and the name of this person.
     */
    public function fullName() 
    {
        return $this->last_name . ', ' . $this->name;
    }

    /**
     * Returns a string that represents the sex associated with this person.
     */
    public function sexToString()
    {
        switch($this->sex)
        {
            case "F":
                return "Femenino";
            case "M":
                return "Masculino";
            case "O":
                return "Otro sexo";
            default:
                return "-";
        }
    }

    public function documentTypeToString()
    {
        switch($this->document_type)
        {
            case Person::DNI:
                return "DNI";
            case Person::PASSPORT:
                return "Pasaporte";
        }
    }

    public function setRequiredDocumentation(Array $data)
    {
        $this->required_documentation = json_encode($data);
    }

    public function getRequiredDocuments()
    {
        return collect(json_decode($this->required_documentation))->filter(function($doc) { return $doc; });
    }

    public function getNotRequiredDocuments()
    {
        return collect(json_decode($this->required_documentation))->filter(function($doc) { return !$doc; });
    }

    public function documentKeyToString($key)
    {
        switch ($key) {
            case 'acc_pers':
                return 'Certificado de cobertura Acc. Pers.';
            case 'art_file':
                return 'Certificado de cobertura ART';
            case 'boarding_card':
                return 'Cédula de embarque';
            case 'boarding_passbook':
                return 'Libreta de embarque';
            case 'company_note':
                return 'Nota de la empresa';
            case 'dni_copy':
                return 'Documento de identidad';
            case 'driver_license':
                return 'Registro de conducir';
            case 'health_notebook':
                return 'Libreta sanitaria';
            case 'pbip_file':
                return 'Constancia de curso PBIP';
            case 'pna_file':
                return 'Número de prontuario';
        }
    }

    /**
     * Given an array, sets the contact json of this person.
     */
    public function setContact(Array $data)
    {
        $this->contact = json_encode([
            'fax'          => $data['fax']          ?? null,
            'email'        => $data['email']        ?? null,
            'home_phone'   => $data['home_phone']   ?? null,
            'mobile_phone' => $data['mobile_phone'] ?? null
        ]);
    }

    /**
     * Decodes and returns the json where the contact information of this person is stored.
     */
    public function contactToObject()
    {
        return json_decode($this->contact);
    }

    public function contactToArray()
    {
        $contact = $this->contactToObject();
        return [
            'email'         => $contact->email          ?? '-',
            'phone'         => $contact->home_phone     ?? '-',
            'mobile_phone'  => $contact->mobile_phone   ?? '-',
            'fax'           => $contact->fax            ?? '-',
        ];
    }

    public function getStorageFolder()
    {
        $mod = $this->id % 10;
        return 'documentation/'.$mod.'/'.$this->id.'/';
    }

    public function getCurrentPicture()
    {
        $path = $this->getStorageFolder() . 'pictures/' . $this->picture_name;
        return \Helpers::getImageAsDataURI($path);
    }

    public function toShowArray() 
    {
        return [
            'enabled'   => $this->enabled ? true : false,
            'personal_information'  => array_merge(
                [
                    'picture'           => $this->getCurrentPicture(),
                    'full_name'         => $this->fullName(),
                    'document_type'     => $this->documentTypeToString(),
                    'document_number'   => $this->document_number,
                    'cuil'              => $this->cuil          ?? '-',
                    'birthday'          => \Helpers::timestampToDate($this->birthday),
                    'sex'               => $this->sexToString(),
                    'blood_type'        => $this->blood_type    ?? '-',
                    'homeland'          => $this->homeland ?? '-',
                    'risk'              => 'Nivel '.$this->risk,
                    'register_number'   => $this->register_number,
                    'pna'               => $this->pna           ?? '-',
                ],
                $this->contactToArray(),
                $this->residency ? $this->residency->toArray() : []
            ),
            'jobs'              => $this->jobs(),
            'vehicles'          => $this->vehicles->toArray(),
            'observations'      => $this->observations->toArray(),
            'documents'         => $this->documents->map(function($document) {
                                        return [
                                            'id'         => $document->id,
                                            'type'       => PersonDocument::typeToString($document->document_type),
                                            'created_at' => \Helpers::timestampToDate($document->created_at),
                                            'expiration' => \Helpers::timestampToDate($document->expiration)
                                        ];
                                    }),
            'required_documents' => $this->getRequiredDocuments()->map(function($doc, $key) {
                                        return $this->documentKeyToString($key);
                                    }),
            'not_required_documents' => $this->getNotRequiredDocuments()->map(function($doc, $key) {
                                            return $this->documentKeyToString($key);
                                        })
        ];
    }

    public function toPdfArray()
    {
        $contact = $this->contactToArray();
        return [
            'full_name'             => $this->fullName(),
            'picture'               => $this->getCurrentPicture(),
            'register_number'       => $this->register_number ?? '-',
            'personal_information'  => [
                'Tipo de documento'     => $this->documentTypeToString(),
                'Número de documento'   => $this->document_number,
                'CUIL'                  => $this->cuil          ?? '-',
                'Fecha de nacimiento'   => \Helpers::timestampToDate($this->birthday),
                'Género'                => $this->sexToString(),
                'Grupo sangíneo'        => $this->blood_type    ?? '-',
                'Nacionalidad'          => $this->homeland ?? '-',
                'Nivel de riesgo'       => 'Nivel '.$this->risk,
                'Nº prontuario PNA'     => $this->pna           ?? '-',
                'Email'                 => $contact['email'],
                'Teléfono fijo'         => $contact['phone'],
                'Teléfono móvil'        => $contact['mobile_phone'],
                'Fax'                   => $contact['fax'],
                'Domicilio'             => $this->residency->toString() 
            ],
            'jobs'                  => $this->jobs()->map(function($job) {
                                            return (object) [
                                                'company_name' => $job->company_name,
                                                'activity_name' => $job->activity_name,
                                                'data'          => [
                                                    'Subactividades' => implode($job->subactivities, ', '),
                                                    'Aseguradora'    => $job->art_company,
                                                    'Nº de socio'    => $job->art_number,
                                                ],
                                                'cards' => $job->cards
                                            ];
                                        }),
        ];
    }
}
