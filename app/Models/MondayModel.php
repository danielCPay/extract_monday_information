<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MondayModel
{

    public static function InsertRecordMonday(array $data): Object
    {             
        $parametros = array(           
            $data[6]['status'],
            $data[1]['text4'],
            $data[2]['person'],
            $data[3]['text7'],
            $data[4]['text9'],
            $data[5]['location'],
            $data[7]['date4'],
            $data[8]['date_1']
        );

        $res = DB::select("CALL sp_insert_item_board(?,?,?,?,?,?,?,?)", $parametros);
        return $res[0] ?? null;
    }
}
