<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class Search extends Model 
{
	protected $table = 'search';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'word', 
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	*/
	protected $hidden = [
			
	];
}
