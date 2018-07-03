<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateCardRequest;

use App\Http\Traits\SaveCardTrait;

use App\Card;
use App\Person;


class CardsController extends Controller
{
    use SaveCardTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "INDEX";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "CREATE";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCardRequest $request)
    {
        // Tries to get the current active card of the person 
        $person_id = $request->person_id;
        $person = Person::find($person_id);
        $current = $person->getActiveCard();
        // If there was an active card, deactivates it.
        // Its done here because we want to be sure of having always and active card, 
        // so we save the new one first
        if($current) {
            $current->active = false;
            $current->save();
        }
        // Creates a new card and saves it to the database as the current active card
        $this->saveCard($person_id);
        // Redirection
        return redirect()->route('people.show', $person_id);
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
        $card = Card::findOrFail($id);

        if(isset($request->active)) {
            $request->validate(['active' => 'boolean']);
            $card->active = $request->active;
        }

        $card->save();

        return redirect()->back();
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
