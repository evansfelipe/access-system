<?php
namespace App\Http\Traits;
use App\Card;

trait SaveCardTrait {
    /**
     * Implemented in:
     * - PeopleController.store
     * - CardsController.store
     */
    public function saveCard($person_id) {
        $card = new Card();
        $card->person_id = $person_id;
        $card->active = true;
        $card->save();
    }
}