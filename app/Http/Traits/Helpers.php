<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\{ Person, Company, Residency };

trait Helpers {
    /**
     * Creates, assigns the data and saves to the database a new residency.
     * 
     * @return Integer $id: the id of the new residency.
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
     * Given a Person and a Request, stores the new data sent on the request on the person attributes.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Person $person
     */
    public static function setPerson(Person $person, Request $request)
    {
        // Assigns the basic person data
        $person->last_name = $request->last_name;
        $person->name = $request->name;
        $person->document_type = $request->document_type;
        $person->document_number = $request->document_number;
        $person->cuil = $request->cuil;
        $person->birthday = $request->birthday;
        $person->sex = $request->sex;
        $person->blood_type = $request->blood_type;
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
        $person->pna = $request->pna;
        $person->contact = json_encode([
            'fax' => $request->fax,
            'email' => $request->email,
            'home_phone' => $request->home_phone,
            'mobile_phone' => $request->mobile_phone
        ]);
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
                'text' => $company->name
            ]);
        }
        return $companies_data;
    }
}