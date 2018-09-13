<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonDocument extends Model
{
    public const company_note = 0;
    public const dni_copy = 1;
    public const pna_file = 2;
    public const driver_license = 3;
    public const art_file = 4;
    public const acc_pers = 5;
    public const boarding_passbook = 6;
    public const boarding_card = 7;
    public const health_notebook = 8;
    public const pbip_file = 9;

    public function getConst($string)
    {
        return constant('App\PersonDocument::' . $string);
    }

    public function typeToString()
    {
        switch ($this->document_type) {
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

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
