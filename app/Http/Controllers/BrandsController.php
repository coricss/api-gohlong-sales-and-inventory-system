<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if(auth()->user()){
                /* join brands and categoiry */
                return Brands::select(
                    'brands.id',
                    'brands.brand_name',
                    'categories.category_name',
                    'brands.created_at',
                    'brands.updated_at'
                )->join('categories', 'brands.category_id', '=', 'categories.id')
                ->orderBy('brands.id', 'desc')->get();
            }else{
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '500',
                'message' => 'Error fetching brands'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()){
            try {
                $brand = Brands::create([
                    'brand_name' => $request->brand_name,
                    'category_id' => $request->category_id
                ]);
                return response()->json([
                    'status' => '200',
                    'message' => 'Brand has been added successfully'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error creating brand'
                ], 500);
            }
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if(auth()->user()){
            try {
                return Brands::select(
                    'brands.id',
                    'brands.brand_name',
                    'categories.category_name',
                    'brands.category_id',
                    'brands.created_at',
                    'brands.updated_at'
                )->join('categories', 'brands.category_id', '=', 'categories.id')
                ->where('brands.id', $id)->first();
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error fetching brand'
                ], 500);
            }
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brands $brands)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()){
            try {
                
                $brand = Brands::find($id);
                $brand->brand_name = $request->brand_name;
                $brand->category_id = $request->category_id;
                $brand->save();

                return response()->json([
                    'status' => '200',
                    'message' => 'Brand has been updated successfully'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error updating brand'
                ], 500);
            }
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(auth()->user()){
            try {
                $brand = Brands::find($id);
                $brand->delete();

                return response()->json([
                    'status' => '200',
                    'message' => 'Brand has been deleted successfully'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error deleting brand'
                ], 500);
            }
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
