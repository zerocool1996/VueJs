<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Author;
use App\Http\Requests\AuthorRequest;
use App\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    protected $author, $user;
    public function __construct(
        Author $author,
        User $user
    ) {
        $this->author = $author;
        $this->user   = $user;
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
        $authors = $this->author->latest()->paginate(10);
        return response()->json([
            'authors' => $authors
        ]);
    }

    public function all()
    {
        $authors = $this->author->all();
        return response()->json([
            'authors' => $authors
        ]);
    }

    public function detail($id)
    {
        $author = $this->author->with('AuthorProduct')->find($id);
        return response()->json([
            'author' => $author
        ]);
    }

    public function store(AuthorRequest $request)
    {
        $data   = $request->except(['img']);
        $author = Author::create($data);
        $this->handleUploadImg($request, $author);
        return response()->json([
            'author' => $author,
            'message' => 'Thêm mới tác giả thành công !'
        ]);
    }

    public function edit($id)
    {
        $author = $this->author->findOrFail($id);
        return response()->json([
            'author' => $author
        ]);
    }

    public function update(AuthorRequest $request, $id)
    {
        dd($request->img);
        $author = $this->author->findOrFail($id);
        $data   = $request->except(['img']);
        DB::beginTransaction();
        try {
            $author->update($data);
            $this->handleUploadImg($request, $author);
            DB::commit();
            return response()->json([
                'author' => $author,
                'message' => 'Sửa đổi tác giả thành công !'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function destroy($id)
    {
        $author = $this->author->findOrFail($id);
        $author->delete();
        return response()->json([
            'message' => 'Xóa tác giả thành công !'
        ]);
    }

    public function search($keyword)
    {
        $authors = $this->author->where('name', 'like', '%' . $keyword . '%')->paginate(10);
        return response()->json([
            'authors' => $authors
        ]);
    }

    public function trang(Request $request)
    {
        if ($this->user->can('view', Author::class)) {
            if ($request->ajax()) {
                $authors = $this->author->where('name', 'like', '%' . $request->keyword . '%')->paginate($request->item_per_page);
            }
            return view('admin.ajax-view.author', compact('authors'))->render();
        } else {
            return redirect()->back()->withErrors('Over Charge !!');
        }
    }

    public function garbage()
    {
        $authors = $this->author->onlyTrashed()->paginate(15);
        return response()->json([
            'authors' => $authors
        ]);
    }

    public function restore($id)
    {
        $author = $this->author->onlyTrashed()->findOrFail($id);
        $author->restore();
        return response()->json([
            'author' => $author,
            'message' => 'Khôi phục thành công !'
        ]);
    }

    public function forceDelete($id)
    {

        $author = $this->author->onlyTrashed()->findOrFail($id);
        $author->forceDelete();
        return response()->json([
            'message' => 'Xóa vĩnh viễn thành công !'
        ]);
    }

    public function searchDeleted($keyword)
    {
        $authors = $this->author->onlyTrashed()->where('name', 'like', '%' . $keyword . '%')->paginate(10);
        return response()->json([
            'authors' => $authors
        ]);
    }

    public function trangDeleted(Request $request)
    {
        if ($this->user->can('view', Author::class)) {
            if ($request->ajax()) {
                $authors = $this->author->onlyTrashed()->where('name', 'like', '%' . $request->keyword . '%')->paginate($request->item_per_page);
            } else {
                $authors = $this->author->onlyTrashed()->paginate(10);
            }
            return view('admin.ajax-view.author-garbage', compact('authors'))->render();
        } else {
            return redirect()->back()->withErrors('Over Charge !!');
        }
    }
}
