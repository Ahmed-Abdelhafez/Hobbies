<?php
header('Access-Control-Allow-Origin: *');
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('startingPage');
});

Route::get('/info', function () {
    return view('infoPage');
});

Route::get('/swagger', function () {
    return view('doc/swagger/index');
});

Route::resource('hobby', 'HobbyController');

Route::resource('tag', 'TagController');

Route::resource('user', 'UserController');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/hobby/tag/{tag_id}',  [App\Http\Controllers\hobbyTagController::class, 'getFilteredHobbies'])->name('tag_hobby');

//Attach and Detach Tags
Route::get('/hobby/{hobby_id}/tag/{tag_id}/attach',  [App\Http\Controllers\hobbyTagController::class, 'attachTag']);
Route::get('/hobby/{hobby_id}/tag/{tag_id}/detach',  [App\Http\Controllers\hobbyTagController::class, 'detachTag']);

// Delete Images of Hobby
Route::get('/delete-images/hobby/{hobby_id}', 'HobbyController@deleteImages');
// Delete Images of User
Route::get('/delete-images/user/{user_id}', 'UserController@deleteImages');