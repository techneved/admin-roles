<?php
Route::name('admin.')->group(function() {

    Route::post('login', 'LoginController@login')->name('login');
    Route::post('logout','LoginController@logout')->name('logout');

});