<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Category;
use App\Models\Brands;
use App\Models\Sales;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* count active products join with brands and categories*/
        $products =  Products::select()->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->count();

        /* count categories */
        $categories = Category::count();

        /* count brands joined with category*/
        $brands = Brands::select()->join('categories', 'brands.category_id', '=', 'categories.id')->count();

        /* count sum of subtotal in sales table in current month and current year */
        $sales = Sales::selectRaw('SUM(subtotal) as total_sales')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->first();
        $sales = (double)$sales->total_sales;

        /* return response array of object */
        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'brands' => Brands::count(),
            'sales' => $sales
        ]);
    }


    public function getWeeklySales()
    {
        /* get weekly sales subtotal */

        ########### THIS WEEK ############# 
        /* last 7 days array  */
        $last_7_days = array();

        for ($i=0; $i < 7; $i++) {
            /* last 7 days monday to friday */
            $last_7_days[$i] = date('Y-m-d', strtotime('-'.$i.' days'));
        }

        /* get sales subtotal in this 7 days */
        for($i=0; $i < count($last_7_days); $i++) {
            $sales = Sales::selectRaw('SUM(subtotal) as total_sales')->whereDate('created_at', $last_7_days[$i])->first();
            $last_7_days[$i] = (double)$sales->total_sales;
        }


        ###### LAST WEEK #########
        /* last 7 days array  */
        $last_7_days_last_week = array();

        for ($i=0; $i < 7; $i++) {
            /* last 7 days monday to friday */
            $last_7_days_last_week[$i] = date('Y-m-d', strtotime('-'.$i.' days', strtotime('-1 week')));
        }

        /* get sales subtotal in this 7 days */
        for($i=0; $i < count($last_7_days_last_week); $i++) {
            $sales = Sales::selectRaw('SUM(subtotal) as total_sales')->whereDate('created_at', $last_7_days_last_week[$i])->first();
            $last_7_days_last_week[$i] = (double)$sales->total_sales;
        }

        /* return response array of object */
        return response()->json([
            'this_week' => array_reverse($last_7_days),
            'last_week' => array_reverse($last_7_days_last_week)
        ]);
    }

 
    public function getTopProducts()
    {
        /* get top 5 products */
        $top_products = Sales::selectRaw('
            sales.product_id, 
            products.model_size,
            SUM(sales.quantity) as total_quantity'
        )->join('products', 'sales.product_id', '=', 'products.product_id')->groupBy('sales.product_id')
        ->orderBy('total_quantity', 'desc')
        ->limit(5)
        ->get();

        /* return response array of object */
        return response()->json($top_products, 200);
    }

}
