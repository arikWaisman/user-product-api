<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'description', 'price', 'image',
	];

	public $timestamps = false;

	/**
	 * set up the realtionship with users
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user(){

		return $this->belongsTo('App\User');

	}
}
