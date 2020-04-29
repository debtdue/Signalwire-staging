<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * A business has many agents
	 * Many to many relationships
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function agents()
	{
		// a business can have many agents
		return $this->belongsToMany( Agent::class );
	}

	/**
	 * Get the account record associated with this business
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function account()
	{
		return $this->hasOne(VoipAccount::class);
	}

	/**
	 * Get the next possible insert ID
	 * @return mixed
	 */
	public function getNextInsertID()
	{

		$statement = \DB::select("show table status like 'businesses'");
		return $statement[0]->Auto_increment;

	}

	/**
	 * A business has many numbers
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function numbers()
	{
		return $this->hasMany(VoipNumber::class);
	}

	/**
	 * A business has many calls record
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function calls()
	{
		return $this->hasMany(Call::class);
	}
}
