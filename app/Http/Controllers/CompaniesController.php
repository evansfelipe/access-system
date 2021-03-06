<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\SaveCompanyRequest;
use App\Http\Traits\{ SaveResidencyTrait };
use App\{Company, Residency, Group};

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
        // Saves the company's groups
        if(isset($request->groups)) {
            foreach ($request->groups as $group) {
                $group['company_id'] = $company->id;
                $gp = new Group($group);
                $gp->daysToChar($group['days']);
                $gp->save();
            }
        }

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
        $groups = $company->groups->map(function($group){
            $days_array = $group->daysToArray();
            return [
                'key'     => $group->id,
                'name'    => $group->name,
                'gate_id' => $group->gate_id,
                'start'   => $group->start,
                'end'     => $group->end,
                'days'          => [
                    'monday'    => $days_array[0] == 1 ? true : false,
                    'tuesday'   => $days_array[1] == 1 ? true : false,
                    'wednesday' => $days_array[2] == 1 ? true : false,
                    'thursday'  => $days_array[3] == 1 ? true : false,
                    'friday'    => $days_array[4] == 1 ? true : false,
                    'saturday'  => $days_array[5] == 1 ? true : false,
                    'sunday'    => $days_array[6] == 1 ? true : false
                ]
            ];
        })->toArray();

        $contact = $company->contactToArray();
        \Debugbar::info($contact);

        $general_information = array_merge([
            'business_name' => $company->business_name,
            'name'          => $company->name,
            'area'          => $company->area,
            'cuit'          => $company->cuit,
            'expiration'    => $company->expiration ? date('Y-m-d', strtotime($company->expiration)) : '',
            'phone'         => $contact['phone'],
            'email'         => $contact['email'],
            'web'           => $contact['web'] !== '-' ? $contact-['web'] : '',
            'fax'           => $contact['fax'] !== '-' ? $contact-['fax'] : '',
        ],
        $company->residency->toArray());

        $data = [
            'id'    => $company->id,
            'values' => [
                'general_information'   => $general_information,
                'assign_groups'         => [
                    'groups'            => $groups
                ]
            ]
        ];
        \Debugbar::info($data);
        if( $data['values']['general_information']['apartment'] === '-' ) $data['values']['general_information']['apartment'] = '';

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
        // \Debugbar::info($request->toArray());
        $company->fill($request->toArray());
        $company->save();

        $old_groups = [];
        foreach ($company->groups as $group) { array_push($old_groups,$group->id); }

        $existing_groups = [];
        foreach ($request->groups as $group) {
            $existing_group = Group::where('id', $group['key'])->first();
            if($existing_group) {
                $existing_group->fill($group);
                $existing_group->daysToChar($group['days']);
                $existing_group->save();
                array_push($existing_groups, $existing_group->id);
            }
            else {
                $group['company_id'] = $company->id;
                $gp = new Group($group);
                $gp->daysToChar($group['days']);
                $gp->save();
            }
        }

        $removed_groups = array_diff($old_groups, $existing_groups);
        foreach ($removed_groups as $group_id) {
            $group = Group::where('id', $group_id)->first();
            $group->deleteCascade();
        }
         
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
