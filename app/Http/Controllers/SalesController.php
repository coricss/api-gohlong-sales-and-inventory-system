<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Products;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function count_sales_today() {
        if(auth()->user()) {
            try {
                return Sales::whereDate('created_at', date('Y-m-d'))->count();
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error fetching sales'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function revert_transaction(Request $request) {
        if(auth()->user()) {
            try {
                $sales = Sales::where('transaction_id', $request->transaction_id)->get();
                foreach($sales as $sale) {
                    $product = Products::where('product_id', $sale->product_id)->first();

                    if($product->old_stocks > 0) {
                        $product->update([
                            'old_stocks' => $product->old_stocks + $sale->quantity
                        ]);
                    } else {
                        $product->update([
                            'stocks' => $product->stocks + $sale->quantity
                        ]);
                    }
                }

                Sales::where('transaction_id', $request->transaction_id)->delete();

                return response()->json([
                    'status' => '200',
                    'message' => 'Transaction reverted successfully'
                ], 200);
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

    public function index()
    {
        if(auth()->user()) {
            try {
                return Sales::selectRaw(
                    'sales.id,
                    sales.cashier_name,
                    sales.transaction_id,
                    sales.customer_name,
                    products.product_id AS product_id,
                    products.model_size AS model_size,
                    brands.brand_name AS brand_name,
                    categories.category_name AS category_name,
                    products.price AS price,
                    products.discount AS discount,
                    sales.is_discounted,
                    sales.quantity,
                    sales.subtotal,
                    sales.mode_of_payment,
                    sales.ref_no,
                    sales.payment,
                    sales.change,
                    sales.created_at'
                )->join('products', 'sales.product_id', '=', 'products.product_id')
                ->join('brands', 'products.brand_id', '=', 'brands.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->orderBy('sales.id', 'desc')->get();

                /* return Sales::all(); */

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error fetching sales'
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
            try {
                $prod = [];
                foreach($request->items as $item) {
                    $sales = Sales::create([
                        'cashier_name' => $request->cashier_name,
                        'transaction_id' => $request->transaction_id,
                        'customer_name' => $request->customer_name,
                        'product_id' => $item['product_id'],
                        'is_discounted' => $item['is_discounted'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                        'mode_of_payment' => $request->mode_of_payment,
                        'ref_no' => $request->reference_number,
                        'payment' => $request->payment,
                        'change' => $request->change
                    ]);

                    $product = Products::where('product_id', $item['product_id'])->first();

                    if($product->old_stocks > 0) {
                        $product->update([
                            'old_stocks' => $product->old_stocks - $item['quantity']
                        ]);
                    } else {
                        $product->update([
                            'stocks' => $product->stocks - $item['quantity']
                        ]);
                    }
                }

                return response()->json([
                    'status' => '200',
                    'message' => 'Sales added successfully'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => $$th->getMessage()
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
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
