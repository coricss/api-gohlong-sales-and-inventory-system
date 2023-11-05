<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use PDF;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()) {
            try {
                return Products::selectRaw(
                    'products.id,
                    products.product_id,
                    products.model_size,
                    brands.brand_name,
                    categories.category_name,
                    products.stocks,
                    products.price,
                    products.discount,
                    products.price * products.stocks as total_stock_price,
                    products.discount * products.stocks as total_stock_discounted_price,
                    products.created_at,
                    products.updated_at'
                )->join('brands', 'products.brand_id', '=', 'brands.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->orderBy('products.id', 'desc')->get();

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error fetching products'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
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
        if(auth()->user()) {

            $product = Products::create([
                'product_id' => uniqid(),
                'model_size' => $request->model_size,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'stocks' => $request->stocks,
                'price' => $request->price,
                'discount' => $request->discounted_price
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product
            ], 201);
        } else {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if(auth()->user()) {
            try {
                return Products::selectRaw(
                    'products.id,
                    products.product_id,
                    products.brand_id,
                    products.category_id,
                    products.model_size,
                    brands.brand_name,
                    categories.category_name,
                    products.stocks,
                    products.price,
                    products.discount,
                    products.price * products.stocks as total_stock_price,
                    products.discount * products.stocks as total_stock_discounted_price,
                    products.created_at,
                    products.updated_at'
                )->join('brands', 'products.brand_id', '=', 'brands.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.id', $id)
                ->orderBy('products.id', 'desc')->first();

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error fetching products'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
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
                $product = Products::find($id);

                $product->update([
                    'model_size' => $request->model_size,
                    'brand_id' => $request->brand_id,
                    'category_id' => $request->category_id,
                    'stocks' => $request->stocks,
                    'price' => $request->price,
                    'discount' => $request->discounted_price
                ]);

                return response()->json([
                    'message' => 'Product updated successfully',
                    'product' => $product
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error updating product'
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
        if(auth()->user()) {
            try {
                $product = Products::find($id);
                $product->delete();

                return response()->json([
                    'status' => '200',
                    'message' => 'Product deleted successfully'
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error deleting product'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function print_barcode($id) {
        if(auth()->user()) {
            try {
                $product = Products::find($id);

               
                /* generate barcode */
                $barcode = $product->product_id;

                $generator = new BarcodeGeneratorPNG();
                $barcode = base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_128));


                $data = [
                    'stocks' => $product->stocks,
                    'model_size' => $product->model_size,
                    'product_id' => $product->product_id,
                    'barcode' => $barcode
                ];

                $pdf = PDF::loadView('PrintBarcode', $data);

                $pdf->save(public_path('barcodes/'.$product->product_id.'.pdf'));

                return response()->json([
                    'status' => '200',
                    'message' => 'Barcode printed successfully',
                    'product' => $product
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
