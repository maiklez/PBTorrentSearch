<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $app->get('/', function () use ($app) {
//     return $app->version();
// });

$app->get('/', [
		'as' => 'profile', 'uses' => 'PBController@getIndex'
]);

$app->get('/{search}', [
		'as' => 'profile', 'uses' => 'PBController@getSearch'
]);

$app->post('/set/magnet', [
		'as' => 'setmagnet', 'uses' => 'PBController@setMagnet'
]);