<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category_Product;
use App\Category;
use App\Author;
use App\Http\Requests\CategoryRequest;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{
    protected $product, $user;
    protected $category_product;
    public function __construct(
        Product             $product,
        Category            $category,
        Category_Product    $category_product,
        Author              $author,
        User                $user
    ){
        $this->product              = $product;
        $this->category_product     = $category_product;
        $this->category             = $category;
        $this->author               = $author;
        $this->user                 = $user;
    }

    public function index(){
        $categories = $this->category->paginate(10);
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function all(){
        $categories = $this->category->all();
        return response()->json([
            'categories' => $categories
        ]);
    }


    public function store(CategoryRequest $request){
            $data = $request->except('products');
            $category = $this->category->create($data);
            $category->categoryProducts()->attach($request->input('products'));
            return response()->json([
                'data' => $category,
                'message' => 'Thêm mới sản phẩm thành công !'
            ]);
    }

    public function edit($id){
        $category = $this->category->with('categoryProducts:products.id,products.name')->findOrFail($id);
        return response()->json([
            'category' => $category
        ]);
    }

    public function update(CategoryRequest $request, $id){
            $category = $this->category->findOrFail($id);
            $data = $request->except('products');
            DB::beginTransaction();
            try {
                $category->update($data);
                $category->categoryProducts()->sync(collect($request->input('products')));
                DB::commit();
            }catch (Exception $e){
                DB::rollback();
            }
            return response()->json([
                'data' => $category,
                'message' => 'Chỉnh sửa sản phẩm thành công !'
            ]);
    }

    public function destroy($id){

            $category = $this->category->findOrFail($id);
            $category->delete();
            return response()->json([
                'message' => 'Xóa danh mục thành công !'
            ]);
    }

    public function garbage(){

            $categories = $this->category->onlyTrashed()->paginate(10);
            return response()->json([
                'categories' => $categories
            ]);
    }

    public function restore($id){

            $category = $this->category->onlyTrashed()->findOrFail($id);
            $category->restore();
            return response()->json([
                'message' => 'Khôi phục danh mục thành công !'
            ]);
    }

    public function forceDelete($id){

            $category = $this->category->onlyTrashed()->findOrFail($id);
            $category->forceDelete();
            return response()->json([
                'message' => 'Xóa vĩnh viễn danh mục thành công !'
            ]);
    }

    public function search($keyword){
        $categories = $this->category->where('name', 'like', '%'.$keyword.'%')->latest()->paginate(10);
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function searchDeleted($keyword){
        $categories = $this->category->onlyTrashed()->where('name', 'like', '%'.$keyword.'%')->latest()->paginate(10);
        return response()->json([
            'categories' => $categories
        ]);
    }
}
