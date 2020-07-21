<?php

namespace App\Http\Controllers;

use App\Author;
use App\Category;
use App\Http\Controllers\Controller;
use App\Product;

class OtherController extends Controller
{
    public function getAllCategory() {
        //dd(Category::all());
        return response()->json(['categories' => Category::get(['id', 'name'])]);
    }

    public function getAllAuthor() {
        return response()->json(['authors' => Author::all()]);
    }

    public function getAllProduct() {
        return response()->json(['products' => Product::latest()->paginate(20)]);
    }
}
