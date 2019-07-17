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

Route::get('/', 'ProblemController@index')->name('/');
Route::post('/problem/apply', 'ProblemController@applyfilter');
Route::get('/home', 'ProblemController@index')->name('/home');
Auth::routes();
Route::get('/contest', 'ContestController@index')->name('/contest');
Route::get('/submission', 'SubmissionController@index')->name('/submission');
Route::post('/contest/apply', 'ContestController@applyfilter');

Route::get('/contest/update', 'ContestController@updateContest')->name('/contest/update');
Route::get('/problem/update', 'ProblemController@updateProblems')->name('/problem/update');
Route::get('/submission/update', 'SubmissionController@updateSubmission')->name('/submission/update');
Route::get('/updateZahin', 'ZahinController@update')->name('/updateZahin');
Route::get('/submissionZahin', 'ZahinController@index')->name('/submissionZahin');
Route::get('/Zahin/Apply', 'ZahinController@appyfilter');