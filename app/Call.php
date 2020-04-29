<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * A call belongs to a business
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function business()
	{
		return $this->belongsTo( Business::class );
	}

	/**
	 * A call belongs to a specific voip account
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function account()
	{
		return $this->belongsTo(VoipAccount::class,'voip_account_id');
	}
}
