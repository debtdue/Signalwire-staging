<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];


	/**
	 * An agents may belongs to many businesses
	 * Many to many relationships
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function businesses()
	{
		return $this->belongsToMany(Business::class);
	}
}
