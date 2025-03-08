<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/about', function () {
    return view('aboutus');
});
Route::get('/information', function () {
    return view('information');
});
Route::get('/mission', function () {
    return view('mission');
});
Route::get('/direction', function () {
    return view('direction');
});
Route::get('/hours', function () {
    return view('hours');
});
Route::get('/services', function () {
    return view('services');
});

Route::get('/myaccount', function () {
    return view('myaccount');
});
Route::get('/contact', function () {
    return view('contact');
});

