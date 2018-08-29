<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Card, Person, PersonCompany };

class SecurityController extends Controller
{
    public function personInfo($card_number)
    {
        $card = Card::where('number', $card_number)->firstOrFail();
        $person = $card->job->person;
        
        $response = [
            'full_name'         => $person->fullName(),
            'document_type'     => $person->documentTypeToString(),
            'document_number'   => $person->document_number,
            'picture_path'      => $person->getCurrentPicturePath()
        ];

        return response(json_encode($response))->header('Content-Type', 'application/json');
    }
}
