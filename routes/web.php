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

Route::group(['middleware' => 'web'], function () {

    Route::match(['GET', 'POST'], '/',['uses'=>'IndexController@execute','as'=>'home']);
    Route::get('/page/{alias}',['uses'=>'PageController@execute', 'as'=>'page']);

    Route::auth();
    

});

//admin
Route::group(['prefix'=>'admin','middleware' => 'auth'], function () {

    //admin
    Route::get('/', function () {
        

    });

    //admin/pages
    Route::group(['prefix' => 'pages'], function () {

        //admin/pages
        Route::get('/',['uses'=>'PagesController@execute','as'=>'pages']);


        //admin/pages/add
        Route::match(['GET', 'POST'], '/add', ['uses'=>'PagesAddController@execute','as'=>'pagesAdd']);

        //admin/pages/edit/2
        Route::match(['GET', 'POST', 'DELETE'], '/edit/{page}', ['uses'=>'PagesEditController@execute','as'=>'pagesEdit']);

    });


    //admin/portfolios
    Route::group(['prefix' => 'portfolios'], function () {

        //admin/portfolios
        Route::get('/',['uses'=>'PortfolioController@execute','as'=>'portfolio']);


        //admin/portfolios/add
        Route::match(['GET', 'POST'], '/add', ['uses'=>'PortfolioAddController@execute','as'=>'portfolioAdd']);

        //admin/portfolios/edit/2
        Route::match(['GET', 'POST', 'DELETE'], '/edit/{portfolio}', ['uses'=>'PortfolioEditController@execute','as'=>'portfolioEdit']);

    });

    //admin/services
    Route::group(['prefix' => 'services'], function () {

        //admin/services
        Route::get('/',['uses'=>'ServiceController@execute','as'=>'services']);


        //admin/services/add
        Route::match(['GET', 'POST'], '/add', ['uses'=>'ServiceAddController@execute','as'=>'serviceAdd']);

        //admin/services/edit/2
        Route::match(['GET', 'POST', 'DELETE'], '/edit/{service}', ['uses'=>'ServiceEditController@execute','as'=>'serviceEdit']);

    });
    
});