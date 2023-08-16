<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * The conditions and response are following TMC Case Study v2 pdf file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->messages()
            ], 400);
        }

        $data = new Category();
        $data->name = $request->name;
        
        if (!$data->save()) {
            return response()->json([
                "errors" => [
                    "message" => "Internal server error"
                ]
            ], 500);
        }

        return response()->json([
            "data" => $data
        ], 200);
    }
}
