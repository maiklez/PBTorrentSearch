<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class Download extends Model 
{
	protected $table = 'download';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'torrent_name',
					'details',
					'type',
					'name',
					'link',
					'magnet',
					'seeders',
					'leechers',
					'category_A',
					'category_A_link',
					'category_B',					
					'category_B_link'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	*/
	protected $hidden = [
			
	];
}
