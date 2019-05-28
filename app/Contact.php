<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/* Class Contact 

 * @property string $image

*/
class Contact extends Model
{
    protected $fillable = [
        'image', 'first_name', 'last_name', 'email'
    ];


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function contact_number()
	{
		return $this->hasMany(ContactNumbers::class);
	}


}
