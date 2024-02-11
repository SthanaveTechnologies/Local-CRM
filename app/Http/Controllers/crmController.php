<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\File;

class crmController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->header('Authorization');
        if ($token) {
            $data = json_decode(file_get_contents('crm-data.json'));

            $tokenString = str_replace('Bearer ', '', $token);

            if (isset($data->{$tokenString}) && $data->{$tokenString}) {
                return response()->json([
                    'status' => 200,
                    'error' => false,
                    'message' => 'Data fetched',
                    'contacts' => $data->{$tokenString}
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'error' => true,
                    'message' => 'User not found'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'error' => true,
                'message' => 'No authorization has been passed'
            ]);
        }
    }

    public function store(Request $request)
    {
        $token = $request->header('Authorization');
        if ($token) {
            $data = json_decode(file_get_contents('crm-data.json'));

            $tokenString = str_replace('Bearer ', '', $token);
            if (isset($data->{$tokenString}) && $data->{$tokenString}) {
                $data->{$tokenString}[] = json_decode($request->getContent());

                $fileWithData = fopen('crm-data.json', 'w');
                fwrite($fileWithData, json_encode($data));
                fclose($fileWithData);

                return response()->json([
                    'status' => 200,
                    'error' => false,
                    'message' => 'Your data has been saved'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'error' => true,
                    'message' => 'User not found'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'error' => true,
                'message' => 'No authorization has been passed'
            ]);
        }
    }
}
