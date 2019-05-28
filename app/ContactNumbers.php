<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/* Class ContactNumber 

 * @property string $image

*/
class ContactNumbers extends Model
{
  
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'number', 'cell', 'contact_id'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function contact()
	{
		return $this->belongsTo(Contact::class);
	}
}
