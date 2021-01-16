<?php

namespace App\Http\Controllers;

use App\Models\UsedNumber;
use Illuminate\Support\Facades\DB;

class BingoCallerController extends Controller
{
    public function bingoCaller()
    {
        $number = rand(1, 75);
        $value = false;

        $user_number = UsedNumber::select('id', 'number')->get();

        foreach ($user_number as $item) {
            if ($item->number == $number) {
                $value = true;
            }
        }

        if ($value == false) {
            $number_data = new UsedNumber();
            $number_data->number = $number;

            DB::transaction(function () use ($number_data, $number) {
                if ($number_data->save()) {
                    $this->fillCards($number);
                }
            });

        } else {
            if (count($user_number) < 74) {
                $this->bingoCaller();
            } else {
                abort(200, 'Se registraron todos los número del 1 al 75');
            }
        }
    }

    private function fillCards($number)
    {

    }
}
