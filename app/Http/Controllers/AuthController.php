<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

	public function login( Request $request ) {

		$this->validate( $request, [
			'email'    => 'required|email',
			'password' => 'required',
		] );

		$credentials = $request->only( 'email', 'password' );

		try {

			if ( !$token = JWTAuth::attempt( $credentials ) ) {

				return response()->json( [ 'msg' => 'Invalid Credentials' ], 401 );

			}

		} catch ( JWTException $e ) {

			return response()->json( [ 'msg' => 'Could not create token' ], 500 );

		}

		return response()->json( [
			'token'  => $token,
			'user'   => Auth::user(),

		], 200 );

	}

	public function logout( Request $request ) {

		$this->validate( $request, [ 'token' => 'required' ] );

		$token = $request->input( 'token' );

		try {

			JWTAuth::invalidate( $token );
			return response()->json( [ 'success' => 'true' ], 200 );

		} catch ( JWTException $e ) {

			return response()->json( [ 'success' => 'false', 'error' => 'There was an error with logging out' ], 500 );


		}

	}

}
