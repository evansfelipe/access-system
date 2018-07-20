<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\{ Person, Company, Residency, Card };
/**
 * A Trait that contains the most used helpers for setting and manipulating our models.
 * Every method is static and does not save anything to the database.
 */
trait Helpers {

    /**
     * Given a card and a request, transfers the data from the request to the card.
     * The attributes from the request that don't match with a Card model are ignored.
     * 
     * @return Void
     */
    public static function setCard(Card $card, Request $request)
    {
        $card->person_id = $request->person_id;
        $card->risk      = $request->risk;
        $card->active    = $request->active ?? true;
        $card->from      = $request->from;
        $card->until     = $request->until;
    }

    /**
     * Given a residency and a request, transfers the data from the request to the residency.
     * The attributes from the request that don't match with a Residency model are ignored.
     * 
     * @return Void
     */
    public static function setResidency(Residency $residency, Request $request)
    {
        $residency->street    = $request->street;
        $residency->apartment = $request->apartment;
        $residency->cp        = $request->cp;
        $residency->country   = $request->country;
        $residency->province  = $request->province;
        $residency->city      = $request->city;
    }

    /**
     * Given a person and a request, transfers the data from the request to the person.
     * The attributes from the request that don't match with a Person model are ignored.
     * 
     * @return Void
     */
    public static function setPerson(Person $person, Request $request)
    {
        // Assigns the basic columns
        $person->name            = $request->name;
        $person->last_name       = $request->last_name;
        $person->document_type   = $request->document_type;
        $person->document_number = $request->document_number;
        $person->sex             = $request->sex;
        $person->pna             = $request->pna;
        $person->cuil            = $request->cuil;
        $person->birthday        = $request->birthday;
        $person->blood_type      = $request->blood_type;
        // Creates the json for the contact column.
        $person->contact = json_encode([
            'fax'          => $request->fax,
            'email'        => $request->email,
            'home_phone'   => $request->home_phone,
            'mobile_phone' => $request->mobile_phone
        ]);
        // If there is a picture, handles its storing
        if($request->hasFile('picture')) {
            // If we are updating a person, and the person has a picture name assigned, that means there already is a picture
            // of this user loaded on the system, so we need to delete it before storing the new one.
            if(!empty($person->picture_name)) {
                unlink('pictures/'.$person->picture_name);
            }
            // Gets the uploaded picture and his extension
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            // Creates the filename of the image using the unique cuil of the user and the extension of the uploaded file.
            $filename = $request->cuil . '.' . $extension;
            // Saves the image
            $file->move('pictures/', $filename);
            // Assigns the filename to the person data
            $person->picture_name = $filename;
        }
    }
    
    /**
     * Creates a relational array with each company, where the key of each component is a company's id, and the value
     * is the company's name. It's used to display the basic information about the stored companies on the system at some blade views.
     * 
     * @return Array
     */
    public static function companiesDataToKeyValue()
    {
        // Gets all the companies stored on the system
        $companies = Company::orderBy('name','asc')->get();
        // Creates an empty array to send the companies data to the view
        $companies_data = [];
        // Adds each company to the data array as a key => value array
        foreach($companies as $company) {
            array_push($companies_data, [
                'value' => $company->id,
                'text'  => $company->name
            ]);
        }
        return $companies_data;
    }
}