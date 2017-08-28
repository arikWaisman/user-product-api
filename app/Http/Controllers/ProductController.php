<?php


namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class ProductController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$products = Product::all();


		//if there we have more than one product lets display them
		if ( 0 < count( $products ) ) {

			foreach ( $products as $product ) {

				$product->view_product = [
					'href'   => 'api/lawline/v1/product/' . $product->id,
					'method' => 'GET'
				];

			}

			return response()->json( $products, 200 );

		}

		return response()->json( [ 'msg' => 'there are no products go add some!' ], 200 );

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {

		$this->validate( $request, [
			'name'        => 'required',
			'description' => 'required',
			'price'       => 'required',
		] );

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$name = $request->input( 'name' );
		$description = $request->input( 'description' );
		$price = $request->input( 'price' );
		$image = $request->input( 'image' );

		$product = new Product( [
			'name'        => $name,
			'description' => $description,
			'price'       => $price,
			'image'       => $image,
		] );

		if ( $product->save() ) {

			$product->view_product = [
				'href'   => 'api/lawline/v1/product/' . $product->id,
				'method' => 'GET'
			];

			return response()->json( [
				'msg'     => 'product created!',
				'product' => $product,
			], 201 );

		}

		return response()->json( [
			'error' => 'You have to be logged in to do that',
		], 404 );

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {

		$product = Product::where( 'id', $id )->first();

		if ( $product ) {
			$product->view_products = [

				'href'   => 'api/lawline/v1/product',
				'method' => 'GET'

			];

			return response()->json( [ $product ], 200 );
		}

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id ) {

		$this->validate( $request, [
			'name'        => 'required',
			'description' => 'required',
			'price'       => 'required',
		] );

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$product = Product::find( $id );

		$name = $request->input( 'name' );
		$description = $request->input( 'description' );
		$price = $request->input( 'price' );

		if ( $product ) {

			$product->name = $name;
			$product->description = $description;
			$product->price = $price;

			$product->view_product = [
				'href'   => 'api/lawline/v1/product/' . $product->id,
				'method' => 'GET'
			];

			return response()->json( [
				'msg'     => 'product updated',
				'product' => $product,
			], 200 );

		}

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$product = Product::find( $id );

		if ( $product ) {

			$product->delete();

			return response()->json( [
				'msg'    => 'product deleted',
				'create' => [
					'href'            => 'api/lawline/v1/product/',
					'method'          => 'POST',
					'required_params' => 'name, description, price'
				],
			], 200 );

		}
	}

	public function imageUpload( Request $request, $id ) {

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$this->validate( $request, [
			'image' => 'required|image',
		] );

		$product = Product::find( $id );

		if ( $product ) {

			$image = $request->file( 'image' );
			$image->storeAs( 'images', $image->getClientOriginalName(), 'public' );

			$product->image = public_path( 'images/' . $image->getClientOriginalName() );

			$product->view_product = [
				'href'   => 'api/lawline/v1/product/' . $product->id,
				'method' => 'GET'
			];

			return response()->json( [
				'msg'     => 'product updated',
				'product' => $product,
			], 200 );
		}

	}


	public function attachUserToProduct( $id ) {

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$product = Product::find( $id );

		if ( $product && is_null( $product->user_id ) ) {


			//add the requesting user id to the product
			$product->user()->associate( $user->id )->save();

			$product->view_product = [
				'href'   => 'api/lawline/v1/product/' . $product->id,
				'method' => 'GET'
			];

			return response()->json( [
				'msg'     => 'user attached to product',
				'product' => $product,
			], 200 );
		}

		return response()->json( [
			'error' => 'you do not have permission to do that',
		], 404 );

	}

	public function detachUserToProduct( $id ) {

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$product = Product::find( $id );

		//you should only be able to detach users from products if the current user is already attached to this specifc product
		if ( $product && $product->user_id === $user->id ) {

			//remove the requesting user id from the product
			$product->user()->dissociate( $user->id )->save();

			$product->view_product = [
				'href'   => 'api/lawline/v1/product/' . $product->id,
				'method' => 'GET'
			];

			return response()->json( [
				'msg'     => 'user removed from product',
				'product' => $product,
			], 200 );

		}

		return response()->json( [
			'error' => 'you do not have permission to do that',
		], 404 );

	}

	public function listUsersProducts(){

		//get the authenticated user from token, if you are not an authenticated user return an error
		if ( !$user = JWTAuth::parseToken()->authenticate() ) {

			return response()->json( [
				'error' => 'You have to be logged in to do that'
			], 404 );

		}

		$products = Product::where( 'user_id', $user->id )->get();

		//if there we have more than one product lets display them
		if ( 0 < count( $products ) ) {

			foreach ( $products as $product ) {

				$product->view_product = [
					'href'   => 'api/lawline/v1/product/' . $product->id,
					'method' => 'GET'
				];

			}

			return response()->json( $products, 200 );

		}


	}
}
