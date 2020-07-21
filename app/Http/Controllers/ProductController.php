<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(['products' => Product::latest()->paginate(20)]);
    }

    public function detail($id)
    {
        $product = Product::with(['productCategory:categories.id,name', 'productAuthor:id,name'])->findOrFail($id);
        return response()->json([
            'product' => $product
        ]);
    }

    public function mostSale()
    {
        $products = Product::orderBy('saled', 'desc')->take(10)->get();
        return response()->json([
            'products' => $products
        ]);
    }

    public function newestUpdate()
    {
        $products = Product::orderBy('updated_at', 'desc')->take(5)->get();
        return response()->json([
            'products' => $products
        ]);
    }

    public function search($keyword)
    {
        $products = Product::where('name', 'like', '%' . $keyword . '%')->latest()->paginate(20);
        return response()->json([
            'products' => $products
        ]);
    }
}
