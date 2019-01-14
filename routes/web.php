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

Route::get('/', function () {
	//echo'ok';die;
    return view('/dashboard/HomePage')->middleware('auth');
});
Auth::routes();
Route::get('/', 'DashboardController@index')->middleware('auth');
Route::resource('url-list','LinkController')->middleware('auth');
Route::resource('category','CategoryController')->middleware('auth');
Route::resource('links','LinkController')->middleware('auth');
Route::resource('tag','TagController')->middleware('auth');
Route::get('dashboard/change-flag','DashboardController@changeFlag')->middleware('auth');
Route::get('dashboard/linkdetail','DashboardController@linkdetail')->middleware('auth');
Route::get('dashboard/ChangeCategory','DashboardController@ChangeCategory')->middleware('auth');
Route::get('csv-data','DashboardController@csv_view')->middleware('auth');
Route::post('csvToArray','DashboardController@csvToArray')->middleware('auth');
Route::get('csvToexport','DashboardController@csvToexport')->middleware('auth');
Route::any('new_check','DashboardController@new_check')->middleware('auth');
Route::any('new_check_background','DashboardController@new_check_background')->middleware('auth');
Route::any('new_check_background_result','DashboardController@new_check_background_result')->middleware('auth');
Route::any('checkHttp','DashboardController@checkHttp')->middleware('auth');
Route::any('checkIp','DashboardController@checkIp')->middleware('auth');
Route::get('audit/changeaudit_new','DashboardController@changeaudit_new')->middleware('auth');
Route::any('urllist','LinkController@index')->name('urllist')->middleware('auth');
Route::get('dashboard/Change_review','DashboardController@Change_review')->middleware('auth');
Route::get('Check-JCLIB','DashboardController@Check_JCLIB')->middleware('auth');
Route::any('CheckTitle','DashboardController@CheckTitle')->middleware('auth');
Route::any('Get-Meta','BackController@Get_Process_data');
Route::any('Get-Ip','BackController@Get_IP_data');
Route::any('Get-Url','BackController@Get_Url_data');


