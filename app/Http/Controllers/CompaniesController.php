<?php

namespace App\Http\Controllers;

use View;
use App\Http\Traits\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests\SaveCompanyRequest;

use App\Http\Traits\{ SaveResidencyTrait };

use App\{Company, Residency};

class CompaniesController extends Controller
{
    use SaveResidencyTrait;
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Requests\SaveCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCompanyRequest $request)
    {
        // Saves the residency
        $residency = new Residency($request->toArray());
        $residency->save();
        Helpers::storeLocation($residency->city, $residency->province, $residency->country);
        // Sets the new company data
        $company = new Company($request->toArray());
        $company->residency_id = $residency->id;
        $company->contact = json_encode([
            'web'   => $request->web,        
            'fax'   => $request->fax,
            'phone' => $request->phone,
            'email' => $request->email
        ]);
        $company->save();
        return response(json_encode(['id' => $company->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response(json_encode($company->toShowArray()), 200)->header('Content-Type', 'application/json');
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $comp = $company->toShowArray();
        unset($comp['assigned_people'], $comp['assigned_vehicles']);
        unset($comp['general_information']['expiration']);
        $comp['general_information']['expiration'] = $company->expiration ? date('Y-m-d', strtotime($company->expiration)) : '';
        if( $comp['general_information']['apartment'] === '-' ) $comp['general_information']['apartment'] = '';

        $data = [
            'id'    => $company->id,
            'values' => [ 'general_information' => $comp['general_information'] ]
        ];
        return response(json_encode($data), 200)->header('Content-Type', 'application/json'); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $company->fill($request->toArray());
        $company->save();
        
        return response(json_encode(['id' => $company->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
