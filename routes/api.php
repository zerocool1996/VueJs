<?php

use App\Http\Controllers\admin\OtherController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::group(['prefix' => 'admin', 'namespace' => 'admin',], function () {

        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('me', 'AuthController@me');
    });

    Route::get('me', 'AuthController@me');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::post('signup', 'AuthController@signup');
    Route::post('update/{id}', 'AuthController@update');
});

Route::post('/signup', 'AuthController@signup');

Route::middleware(['auth:api', 'admin'])->group(function () {

    Route::group(['prefix' => 'product', 'namespace' => 'admin'], function () {

        Route::get('/', 'ProductController@index');

        Route::get('/all', 'ProductController@all');

        Route::get('/delete/{id}', 'ProductController@destroy');

        Route::get('/edit/{id}', 'ProductController@edit');

        Route::post('/update/{id}', 'ProductController@update');

        //Route::get('/create', 'ProductController@create');

        Route::post('/store', 'ProductController@store');

        Route::get('/garbage', 'ProductController@garbage');

        Route::get('/forcedelete/{id}', 'ProductController@forceDelete');

        Route::get('/restore/{id}', 'ProductController@restore');

        Route::get('/search/{keyword}', 'ProductController@search');

        Route::get('/search-deleted/{keyword}', 'ProductController@searchDeleted');
    });

    Route::group(['prefix' => 'categories', 'namespace' => 'admin'], function () {

        Route::get('/', 'CategoryController@index');

        Route::get('/all', 'CategoryController@all');

        Route::get('/delete/{id}', 'CategoryController@destroy');

        Route::get('/edit/{id}', 'CategoryController@edit');

        Route::post('/update/{id}', 'CategoryController@update');

        Route::post('/store', 'CategoryController@store');

        Route::get('/garbage', 'CategoryController@garbage');

        Route::get('/forcedelete/{id}', 'CategoryController@forceDelete');

        Route::get('/restore/{id}', 'CategoryController@restore');

        Route::get('/search/{keyword}', 'CategoryController@search');

        Route::get('/search-deleted/{keyword}', 'CategoryController@searchDeleted');
    });

    Route::group(['prefix' => 'authors', 'namespace' => 'admin'], function () {

        Route::get('/', 'AuthorController@index');

        Route::get('/all', 'AuthorController@all');

        Route::get('/delete/{id}', 'AuthorController@destroy');

        Route::get('/edit/{id}', 'AuthorController@edit');

        Route::post('/update/{id}', 'AuthorController@update');

        Route::post('/store', 'AuthorController@store');

        Route::get('/garbage', 'AuthorController@garbage');

        Route::get('/forcedelete/{id}', 'AuthorController@forceDelete');

        Route::get('/restore/{id}', 'AuthorController@restore');

        Route::get('/search/{keyword}', 'AuthorController@search');

        Route::get('/search-deleted/{keyword}', 'AuthorController@searchDeleted');
    });

    Route::group(['prefix' => 'customers', 'namespace' => 'admin'], function () {

        Route::get('/', 'CustomerController@index');

        Route::get('/edit/{id}', 'CustomerController@edit');

        Route::post('/update/{id}', 'CustomerController@update');

        Route::post('/store', 'CustomerController@store');

        Route::get('/search/{keyword}', 'CustomerController@search');
    });

    Route::group(['prefix' => 'orders', 'namespace' => 'admin'], function () {

        Route::get('/', 'OrderController@index');

        Route::get('/detail/{id}', 'OrderController@detail');

        Route::get('/type/{type}', 'OrderController@type');

        Route::get('/confirm/{id}', 'OrderController@confirm');
    });
});
Route::get('/index', 'ProductController@index');

Route::get('/search/{keyword}', 'ProductController@search');

Route::get('/product/detail/{id}', 'ProductController@detail');

Route::get('/all/category', 'admin\CategoryController@all');

Route::get('/all/author', 'admin\AuthorController@all');

Route::get('/author/detail/{id}', 'admin\AuthorController@detail');

Route::get('/category/detail/{id}', 'admin\CategoryController@edit');

Route::get('product/most-sale', 'ProductController@mostSale');

Route::get('product/newest-update', 'ProductController@newestUpdate');

Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

    Route::post('/vnpay', 'OrderController@vnpay');

    Route::post('/order/store', 'OrderController@store');

    Route::get('/order/ordered', 'OrderController@ordered');

    Route::get('/order/detail/{id}', 'OrderController@detail');

    Route::group(['prefix' => 'cart'], function () {

        Route::post('/init', 'CartController@init');

        Route::get('/add-product/{id}', 'CartController@addProduct');

        Route::get('/delete-product/{id}', 'CartController@deleteProduct');

        Route::get('/reset', 'CartController@reset');

        Route::get('/plus/{id}/{quantity}', 'CartController@plus');

        Route::get('/minus/{id}/{quantity}', 'CartController@minus');

    });
});
