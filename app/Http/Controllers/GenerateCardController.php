<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateCardController extends Controller
{
    private $bingo = ['B', 'I', 'N', 'G', 'O'];

    public function generateUser(Request $request)
    {
        $user = new User();
        $user->name = $request->name;

        DB::transaction(function () use ($user) {
            if ($user->save()) {
                $this->generateCard($user->id);
            }
        });
    }

    private function generateCard($user_id)
    {
        $card = new Card();
        $card->user_id = $user_id;
        if ($card->save()) {
            $this->saveCardNumbers($card->id);
        }
    }

    private function saveCardNumbers($card_id)
    {
        foreach ($this->bingo as $b) {
            $this->generateColumnNumbers($b, $card_id);
        }
    }

    private function generateColumnNumbers($column, $card_id)
    {
        $number = null;

        for ($i = 1; $i < 6; $i++) {
            switch ($column) {
                case "B":
                    $number = rand(1, 15);
                    break;
                case "I":
                    $number = rand(16, 30);
                    break;
                case "N":
                    $number = rand(31, 45);
                    break;
                case "G":
                    $number = rand(46, 60);
                    break;
                case "O":
                    $number = rand(61, 75);
                    break;
            }

            $this->saveData($number, $column . '-' . $i, $card_id);
        }
    }

    private function saveData($number, $column, $card_id)
    {
        $card_number = new CardNumber();
        $card_number->number = $column == 'N-3' ? 0 : $number;
        $card_number->column = $column;
        $card_number->card_id = $card_id;
        $card_number->save();
    }
}
