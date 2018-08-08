<?php

namespace App\Http\Controllers;

use View;
use Illuminate\Http\Request;

use App\Http\Requests\SaveCompanyRequest;

use App\{Company, Residency};

use App\Http\Traits\{ SaveResidencyTrait };


class CompaniesController extends Controller
{
    use SaveResidencyTrait;

    public function updated_at()
    {
        return Company::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    public function list()
    {
        return response(json_encode(Company::all(['id','name'])))->header('Content-Type', 'application/json');        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('companies.create')->render();
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
        $company = new Company();
        $company->name = $request->name;
        $company->area = $request->area;
        $company->cuit = $request->cuit;
        $company->residency_id = $residency->id;
        $company->contact = json_encode([
            'web'   => $request->web,        
            'fax'   => $request->fax,
            'phone' => $request->phone,
            'email' => $request->email
        ]);
        $company->expiration = $request->expiration;
        $company->save();
        return response(json_encode(['id' => $company->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
