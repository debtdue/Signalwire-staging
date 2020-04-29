<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoipAccount extends Model{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * Get the numbers for the Twilio account
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function numbers()
	{
		return $this->hasMany( 'App\VoipNumber' );
	}

	/**
	 * Get the business that owns the account
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function business()
	{
		return $this->belongsTo(Business::class);
	}


	public function calls()
	{
		return $this->hasMany(Call::class);
	}
}
