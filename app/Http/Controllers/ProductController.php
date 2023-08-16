<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Search products data with or without conditions.
     * The conditions and response are following TMC Case Study v2 pdf file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $size = (int) ($request->input("size") ?? 10);
        $tempSkus = $request->input("sku") ?? [];
        $names = $request->input("name") ?? [];
        $priceStart = $request->input("price_start") ?? null;
        $priceEnd = $request->input("price_end") ?? null;
        $stockStart = $request->input("stock_start") ?? null;
        $stockEnd = $request->input("stock_end") ?? null;
        $categoryNames = $request->input("category_name") ?? [];
        $tempCategoryIds = $request->input("category_id") ?? [];

        $skus = [];
        foreach($tempSkus as $sku){
            if ($sku) array_push($skus, $sku);
        }

        $categoryIds = [];
        foreach($tempCategoryIds as $categoryId){
            if ($categoryId) array_push($categoryIds, $categoryId);
        }

        $data = Product::with(['category:id,name'])
            ->when(count($skus) > 0, function($query) use($skus){
                $query->whereIn("sku", $skus);
            })->when(count($names) > 0, function($query) use($names){
                foreach($names as $key => $value){
                    if ($key == 0) $query->where("name", "like", "%$value%");
                    if ($key > 0) $query->orWhere("name", "like", "%$value%");
                }
            })->when($priceStart, function($query) use($priceStart){
                $query->where("price", ">=", $priceStart);
            })->when($priceEnd, function($query) use($priceEnd){
                $query->where("price", "<=", $priceEnd);
            })->when($stockStart, function($query) use($stockStart){
                $query->where("stock", ">=", $stockStart);
            })->when($stockEnd, function($query) use($stockEnd){
                $query->where("stock", "<=", $stockEnd);
            })->when(count($categoryIds) > 0, function($query) use($categoryIds){
                $query->whereHas("category", function($query) use($categoryIds){
                    $query->whereIn("categories.id", $categoryIds);
                });
            })->when(count($categoryNames) > 0, function($query) use($categoryNames){
                $query->whereHas("category", function($query) use($categoryNames){
                    foreach($categoryNames as $key => $value){
                        if ($key == 0) $query->where("categories.name", "like", "%$value%");
                        if ($key > 0) $query->orWhere("categories.name", "like", "%$value%");
                    }
                });
            })->paginate($size);

        return response()->json([
            "data" => $data->getCollection(),
            "paging" => [
                "size" => $size,
                "total" => $data->total(),
                "current" => $data->currentPage()
            ]
        ], 200);
    }

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
            'sku' => 'required|unique:products',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->messages()
            ], 400);
        }

        $data = new Product();
        $data->sku = $request->sku;
        $data->name = $request->name;
        $data->price = $request->price;
        $data->stock = $request->stock ?? 0;
        $data->categoryId = $request->categoryId;
        
        if (!$data->save()) {
            return response()->json([
                "errors" => [
                    "message" => "Internal server error"
                ]
            ], 500);
        }

        $data->load('category:id,name');

        return response()->json([
            "data" => $data
        ], 200);
    }
}
