<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoipNumber extends Model{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'capabilities' => 'array',
	];


	/**
	 * Get the account that owns the number
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function account()
	{
		return $this->belongsTo( 'App\VoipAccount', 'voip_account_id' );
	}

}
