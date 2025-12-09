<?php

use Rabbit\Utils\AdminNotice;
use BookManager\App\App;


function boman_init(){
     return App::get();
}

function boman_handle_try_catch_error($exception){
    /**
     * Print the exception message to admin notice area
     */
    add_action('admin_notices', function () use ($exception) {
        AdminNotice::permanent(['type' => 'error', 'message' => $exception->getMessage()]);
    });

    /**
     * Log the exception to file
     */
    add_action('init', function () use ($exception) {
        if (App::get()->has('logger')) {
            App::get()->get('logger')->warning($exception->getMessage());
        }
    });
}