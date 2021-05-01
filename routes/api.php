<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup','Api\AuthController@signup');

Route::post('/login','Api\AuthController@login');

Route::post('/newrichtext','Api\RicheditorController@ajoutericch');

Route::post('/password/email','Api\ForgotPasswordController@sendResetLinkEmail');

Route::post('/password/reset','Api\ResetPasswordController@reset');


Route::get('/email/resend', 'Api\VerificationController@resend')->name('verification.resend');

Route::get('/email/verify/{id}/{hash}', 'Api\VerificationController@verify')->name('verification.verify');

Route::get('getalltext','Api\RicheditorController@getText');



//Dictionary opertions

//afficher tous les dictionnaires
Route::get('dictionaire','Api\DictionaryController@getDictionary');
//Route::post('dictionary/user/{id}','Api\DictionaryController@userPosts');


Route::middleware('auth:api')->group( function (){
    Route::resource('dictionary', 'DictionaryController');
	Route::get('dictionary/user/{id}', 'Api\DictionaryController@userPosts');
	Route::delete('dictionary/delete/{id}', 'Api\DictionaryController@destroy');
	Route::post('dictionary/create', 'Api\DictionaryController@store');
	Route::post('dictionary/update/{id}', 'Api\DictionaryController@update');

});


//spatie ANd permission ,,, Medaicon.net 

// Route::post('register', 'API\UserController@register');

Route::middleware('auth:api')->group( function () {
	// Route::post('details', 'API\UserController@details');
	// Route::get('list', 'API\UserController@index');
	Route::resource('users', 'API\UserController');
	Route::get('roles', 'API\PermissionController@role_list');
	Route::get('users_list', 'API\PermissionController@listusers');

	Route::post('roles', 'API\PermissionController@role_store');
	Route::get('permissions', 'API\PermissionController@permission_list');
	Route::post('permissions', 'API\PermissionController@permission_store');
	Route::post('rolepermissions/{role}', 'API\PermissionController@role_has_permissions');
	Route::post('assignuserrole/{role}', 'API\PermissionController@assign_user_to_role');

	Route::post('assignroletouser/{user}', 'API\PermissionController@assign_role_to_user');


	Route::post('edit-role-name/{id}', 'API\PermissionController@update');//modifier le nom d'un role

	Route::post('edit-permission-name/{id}', 'API\PermissionController@updatepermissions');
	Route::put('edit-role/{id}', 'API\PermissionController@editRole');

	Route::delete('delete-role/{id}', 'API\PermissionController@destroy');

	Route::delete('delete-permission/{id}', 'API\PermissionController@destroyPermission');



	Route::get('get-specefic-role/{id}', 'API\PermissionController@getrole');

	Route::get('get-specefic-permission/{id}', 'API\PermissionController@getPermission');


	
});
// Route::middleware(['auth:api', 'isAdmin'])->group( function () {
// 	Route::resource('users', 'API\UserController');
// });

Route::get('users/{user}/posts', 'API\UserController@posts');
Route::resource('posts', 'API\PostController');
Route::fallback(function(){
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');
