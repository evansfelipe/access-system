<?php

namespace App\Http\Controllers;

use View;
use Illuminate\Http\Request;
use App\Http\Requests\SaveCompanyRequest;

use App\Http\Traits\{ SaveResidencyTrait };

use App\{Company, Residency};

class CompaniesController extends Controller
{
    use SaveResidencyTrait;

    public function updated_at()
    {
        return Company::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    public function list()
    {
        $companies = Company::select('id','business_name','name','area','cuit')->orderBy('created_at','desc')->get();
        return  response(json_encode($companies))->header('Content-Type', 'application/json');        
    }

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
