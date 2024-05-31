<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Logic\MondayLogic;
use Illuminate\Http\Request;


class MondayController extends Controller
{
    public function __construct()
    {
    }
    public function ReadApiMonday(Request $request)
    {
        $response = new ApiResponse();
        try {
            $datos = (array)$request->input();
            $item_id = $datos['item_id'];

            $response_api_monday = MondayLogic::ReadApiMonday($item_id);
            $data = $response_api_monday['data']['items'][0]['column_values'];

            foreach ($data as $column_values) {
                $item[] = array($column_values['id'] => $column_values['text']);
            };
           
            $response = MondayLogic::InsertRecordApiMonday($item);
        } catch (\Exception $e) {
            return ApiResponse::error('Error' . $e, 404, $response);
        }
        return ApiResponse::success("ok", 200, $response, false);
    }
}
