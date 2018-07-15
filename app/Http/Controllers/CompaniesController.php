<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Company\SaveCompanyRequest;

use App\{Company, Residency};

use App\Http\Traits\{SaveCardTrait, SaveResidencyTrait};


class CompaniesController extends Controller
{
    use SaveResidencyTrait;

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
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Requests\Company\SaveCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCompanyRequest $request)
    {
        // Sets the new company data
        $company = new Company();
        $company->name = $request->name;
        $company->area = $request->area;
        $company->cuit = $request->cuit;
        $company->residency_id = SaveResidencyTrait::saveResidency($request);
        $company->contact = json_encode([
            'phone' => $request->phone,
            'fax' => $request->fax,
            'email' => $request->email,
            'web' => $request->web        
        ]);
        $company->expiration = $request->expiration;
        $company->save();
        // Redirects the user to the recently created company page
        return redirect()->route('companies.show', $company->id);
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
