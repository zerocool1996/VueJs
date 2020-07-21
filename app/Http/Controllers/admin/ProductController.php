<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Carbon;
use App\Product;
use App\Category_Product;
use App\Category;
use App\Author;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

use function GuzzleHttp\Promise\all;

class ProductController extends Controller
{
    protected $product;
    protected $category_product;
    protected $author;
    protected $category;
    protected $user;
    public function __construct(
        Product             $product,
        Category            $category,
        Category_Product    $category_product,
        Author              $author,
        User                $user
    ) {
        $this->product              = $product;
        $this->category_product     = $category_product;
        $this->category             = $category;
        $this->author               = $author;
        $this->user                 = $user;
    }

    public function handleUploadImg($request, $data)
    {
        $old_img = $data->img ?? null;
        if ($request->input('img')) {
            $image = $request->input('img');
            $dataImage = explode(',', $image)[1];
            $type = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $filePath = \Config::get('app.env') . '/images/';
            $fileName = $filePath . time() . '.' . rand() . '.' . $type;
            Storage::disk('public')->put($fileName, base64_decode($dataImage));
            $data->update([
                'img' => $fileName
            ]);
            if ($old_img) {
                Storage::disk('public')->delete($old_img);
            }
        }
    }

    public function index()
    {
        $products = $this->product->with('productAuthor:id,name')->latest()->paginate(10);
        return response()->json([
            'products' => $products
        ]);
    }

    public function all()
    {
        $products = $this->product->all();
        return response()->json([
            'products' => $products
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->except(['img', 'categories']);
        $product = $this->product->create($data);
        $product->productCategory()->attach(collect($request->input('categories')));
        $this->handleUploadImg($request, $product);
        return response()->json([
            'data' => $product,
            'message' => 'Thêm mơi sản phẩm thành công !'
        ]);
    }

    public function edit($id)
    {
        $product = $this->product->with('productCategory:categories.id')->findOrFail($id);
        return response()->json([
            'product'   => $product
        ]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->product->findOrFail($id);
        $data = $request->except(['img', 'categories']);
        // DB::beginTransaction();
        // try {
        $product->update($data);
        $product->productCategory()->sync(collect($request->input('categories')));
        $this->handleUploadImg($request, $product);
        //DB::commit();
        // } catch(Exception $e) {
        //     DB::rollback();
        // }
        return response()->json([
            'data' => $product,
            'message' => 'Chỉnh sửa sản phẩm thành công !'
        ]);
    }

    public function destroy($id)
    {
        $product = $this->product->findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Xóa sản phẩm thành công !']);
    }

    public function garbage()
    {
        $products = $this->product->onlyTrashed()->paginate(10);
        return response()->json([
            'products' => $products
        ]);
    }

    public function forceDelete($id)
    {
        $product = $this->product->onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        return response()->json(["message" => "Đã xóa vĩnh viễn sản phẩm !"]);
    }

    public function restore($id)
    {
        $product = $this->product->onlyTrashed()->findOrFail($id);
        $product->restore();
        return response()->json(["message" => "Đã khôi phục sản phẩm !"]);
    }

    public function search($keyword)
    {

        $products = $this->product->where('name', 'like', '%' . $keyword . '%')->with('productAuthor:id,name')->latest()->paginate(10);
        return response()->json([
            'products' => $products
        ]);
    }

    public function searchDeleted($keyword)
    {
        $products = $this->product->onlyTrashed()->where('name', 'like', '%' . $keyword . '%')->with('productAuthor:id,name')->latest()->paginate(10);
        return response()->json([
            'products' => $products
        ]);
    }

    public function trangDeleted(Request $request)
    {
        if ($this->user->can('view', Product::class)) {
            if ($request->ajax()) {
                $products = $this->product->onlyTrashed()->where('name', 'like', '%' . $request->keyword . '%')->paginate($request->item_per_page);
            } else {
                $products = $this->product->onlyTrashed()->where('name', 'like', '%' . $request->keyword . '%')->paginate(10);
            }
            return view('admin.ajax-view.product-garbage', compact('products'))->render();
        } else {
            return redirect()->route('admin.index')->withErrors('Bạn không đủ thẩm quyền !');
        }
    }
}
