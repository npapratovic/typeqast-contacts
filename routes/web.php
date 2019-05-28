<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 
//redirect to the students resource controller
Route::get('/', function () {
    return redirect('/contacts');
});

Route::resource('contacts','ContactController');
 
Route::patch('contacts/phone-item/{item}/', ['uses' => 'ContactController@updatePhoneNumbers', 'as' => 'contacts.patch_contact']);

Route::group(['middleware' => ['auth']], function () { 

	Route::any('/contacts/add-favorite', ['uses' => 'ContactController@addFavorite', 'as' => 'contacts.add_favorite']);
 	
 	Route::any('/contacts/remove-favorite', ['uses' => 'ContactController@removeFavorite', 'as' => 'contacts.remove_favorite']);

	Route::any('/my-favorites', ['uses' => 'ContactController@myFavorites', 'as' => 'my_favorites']);

});

Route::any('/search', ['uses' => 'ContactController@search', 'as' => 'contacts.search']);

Auth::routes();

Route::get('/home', function () {
    return redirect('/contacts');
});