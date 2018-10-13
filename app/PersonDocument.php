<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonDocument extends Model
{
    public const TYPES = [
        'company_note'      => 0,
        'dni_copy'          => 1,
        'pna_file'          => 2,
        'driver_license'    => 3,
        'art_file'          => 4,
        'acc_pers'          => 5,
        'boarding_passbook' => 6,
        'boarding_card'     => 7,
        'health_notebook'   => 8,
        'pbip_file'         => 9
    ];

    public static function typeToString($document_type)
    {
        switch ($document_type) {
            case 0:
                return 'Nota de la Empresa';
            case 1:
                return 'Documento de identidad';
            case 2:
                return 'Número de prontuario';
            case 3:
                return 'Registro de conducir';
            case 4:
                return 'Certificado de cobertura ART';
            case 5:
                return 'Certificado de cobertura Acc. Pers.';
            case 6:
                return 'Libreta de embarque';
            case 7:
                return 'Cédula de embarque';
            case 8:
                return 'Libreta sanitaria';
            case 9:
                return 'Constancia de curso PBIP';
            default:
                # code...
                break;
        }
    }

    public function getConst($string)
    {
        return PersonDocument::TYPES[$string];
    }

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
