<?php

use Illuminate\Support\Facades\Route;

Route::get('zoho', 'ZohoController@zoho');
Route::get('oauth2back', 'ZohoController@oauth2back');


