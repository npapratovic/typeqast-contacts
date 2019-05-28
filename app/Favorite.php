<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/* Class Favorite 

 * @property string $image

*/
class Favorite extends Model
{
  
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'contact_id', 'user_id'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function favorite()
	{
		return $this->belongsTo(User::class);
	}
}
