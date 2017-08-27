<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		DB::table( 'users' )->insert(
			[
				[
					'firstname' => 'John',
					'lastname'  => 'Doe',
					'email'     => 'johndoe@gmail.com',
					'password'  => bcrypt( 'secret' ),
				],
				[
					'firstname' => 'Steve',
					'lastname'  => 'Smith',
					'email'     => 'stevesmith@gmail.com',
					'password'  => bcrypt( 'secret' ),
				],
				[
					'firstname' => 'Jennifer',
					'lastname'  => 'Smithaven',
					'email'     => 'jennifersmithaven@gmail.com',
					'password'  => bcrypt( 'secret' ),
				],
				[
					'firstname' => 'Brandon',
					'lastname'  => 'Hans',
					'email'     => 'brandonhans@gmail.com',
					'password'  => bcrypt( 'secret' ),
				],
				[
					'firstname' => 'Sydney',
					'lastname'  => 'Jones',
					'email'     => 'sydneyjones@gmail.com',
					'password'  => bcrypt( 'secret' ),
				]
			]
		);

	}
}
