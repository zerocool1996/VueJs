<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class HomeController extends Controller
{
    protected $product;
    protected $category_product;
    protected $author;
    protected $category;
    public function __construct(
        Product             $product,
        Category            $category
    ){
        $this->product              = $product;
        $this->category             = $category;
    }

    public function index(){
        $products = $this->product->orderBy('created_at', 'desc')->take(20)->get();
        return view('index', compact('products'));
    }

    public function all(){
        $products = $this->product->paginate(24);
        return view('all', compact('products'));
    }

    public function show($id){
        $product = $this->product->findOrFail($id);
        return view('product', compact('product'));
    }

    public function category($id){
        $category1 = $this->category->find($id);
        $products = $category1->categoryProducts()->paginate(20);
        if($products){
            return view('category', compact('products', 'category1'));
        }
    }
}
