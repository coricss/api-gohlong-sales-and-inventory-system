<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (auth()->user()) {
                return Category::select(
                    'id',
                    'category_name',
                    'created_at',
                    'updated_at'
                )->orderBy('id', 'desc')->get();
            } else {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '500',
                'message' => 'Error fetching categories'
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
        if (auth()->user()) {
            try {
                $category = Category::create([
                    'category_name' => $request->category_name,
                ]);

                return response()->json([
                    'status' => '200',
                    'message' => 'Category has been added successfully'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error creating category'
                ], 500);
            }
        } else {
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
        if (auth()->user()) {
            return Category::select(
                'id',
                'category_name',
                'created_at',
                'updated_at'
            )->where('id', $id)->first();
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()) {
            try {
                
                $category = Category::find($id);
                $category->category_name = $request->category_name;
                $category->save();

                return response()->json([
                    'status' => '200',
                    'message' => 'Category has been updated successfully'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error updating category'
                ], 500);
            }
        } else {
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
        if (auth()->user()) {
            try {
                $category = Category::find($id);
                $category->delete();

                return response()->json([
                    'status' => '200',
                    'message' => 'Category has been deleted successfully'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error deleting category'
                ], 500);
            }
        } else {
            
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
