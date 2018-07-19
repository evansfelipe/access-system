<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveCardRequest;

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
     * @param  \App\Http\Requests\SaveCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCardRequest $request)
    {
        $person_id = $request->person_id;
        // Gets the person associated with the card's person_id
        $person = Person::find($person_id);
        // If there is an active card, deactivates it before creating the new active card
        $current = $person->getActiveCard();
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
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
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
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        //
    }
}
