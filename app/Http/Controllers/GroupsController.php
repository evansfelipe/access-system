<?php namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\{ Group };

class GroupsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), Group::getValidationRules())->validate();
        $group = new Group($request->toArray());
        $group->daysToChar($request->days);
        $group->save();
        return response(json_encode(['id' => $group->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return response(json_encode($group->toShowArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $days_array = $group->daysToArray();
        $data = [
            'id'     => $group->id,
            'values' => [
                'general_information' => [
                    'name'          => $group->name,
                    'company_id'    => $group->company_id,
                    'gate_id'       => $group->gate_id,
                    'start'         => date('H:i', strtotime($group->start)),
                    'end'           => date('H:i', strtotime($group->end)),
                    'days'          => [
                        'monday'    => $days_array[0] == 1 ? true : false,
                        'tuesday'   => $days_array[1] == 1 ? true : false,
                        'wednesday' => $days_array[2] == 1 ? true : false,
                        'thursday'  => $days_array[3] == 1 ? true : false,
                        'friday'    => $days_array[4] == 1 ? true : false,
                        'saturday'  => $days_array[5] == 1 ? true : false,
                        'sunday'    => $days_array[6] == 1 ? true : false
                    ]
                ]
            ]
        ];
        return response(json_encode($data), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        Validator::make($request->all(), Group::getValidationRules())->validate();
        $group->fill($request->toArray());
        $group->daysToChar($request->days);
        $group->save();
        return response(json_encode(['id' => $group->id]), 200)->header('Content-Type', 'application/json');
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
