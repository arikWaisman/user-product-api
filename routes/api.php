<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group( [ 'prefix' => 'lawline/v1' ], function () {

	Route::post( 'login', 'AuthController@login' );

	Route::get( 'logout', 'AuthController@logout' )->middleware( 'jwt.auth' );

	Route::resource( 'product', 'ProductController', [ 'middleware' => 'jwt.auth', 'except' => [ 'edit', 'create' ] ] );

	Route::post('product/{id}/image', [
		'uses' => 'ProductController@imageUpload'
	])->name('product.image')->middleware( 'jwt.auth' );

	Route::get('product/{id}/attach', [
		'uses' => 'ProductController@attachUserToProduct'
	])->name('product.user.attach')->middleware( 'jwt.auth' );

	Route::get('product/{id}/detach', [
		'uses' => 'ProductController@detachUserToProduct'
	])->name('product.user.detach')->middleware( 'jwt.auth' );

	Route::get('my_products', [
		'uses' => 'ProductController@listUsersProducts'
	])->name('users.products')->middleware( 'jwt.auth' );


} );

