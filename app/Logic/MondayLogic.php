<?php

namespace App\Logic;

use App\Models\MondayModel;
use App\Class\General;

class MondayLogic
{

    public static function ReadApiMonday()
    {
        $urlbase = "https://api.monday.com/v2";

        #$qry = http_build_query($qry);
        $query = <<<'GRAPHQL'
        query GetItem{    
            items (ids:6344568545) {  
              id  
              name  
              column_values {  
                id  
                value
                text
                }
              subitems{
                  id
                  name
                  column_values{
                      id
                      value
                      text
                  }
              }
                }
              }         
        GRAPHQL;
        $payload = json_encode(['query' => $query], JSON_THROW_ON_ERROR);

        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $urlbase,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjMxOTY2ODI2NSwiYWFpIjoxMSwidWlkIjo1NDE0NzY0MCwiaWFkIjoiMjAyNC0wMi0wOVQxOTozNDoxNi4wMDBaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6MjA2NTY4MzYsInJnbiI6InVzZTEifQ.XxOuJj-leOJv25YiF3EPWfZITM-oZ-JJ1TO4W8Zh86g",
                "Content-type: application/json"
            ],
            CURLOPT_POSTFIELDS => $payload
        );

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $result = json_decode($response, true);

        if ($err) {
            $result = "CURL Error #:" . $err;
        }
        return $result['data'];
    }

    public static function InsertRecordApiMonday($item)
    {
        $response = MondayModel::InsertRecordMonday($item);

        if (!General::isEmpty($response)) {
            $message = $response->message;
            unset($response->message);
            $success = ($response->success == 0 ? FALSE : TRUE);
            unset($response->success);
            if (!$success) {
                $response = null;
            }
        }

        return [$response, $message, $success];
    }
}
