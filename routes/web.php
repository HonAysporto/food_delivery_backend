<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-email', function () {

    Mail::to('ayomideoluwafemi2019@gmail.com')->send(new TestMail());

    return 'Email Sent Successfully!';
});
