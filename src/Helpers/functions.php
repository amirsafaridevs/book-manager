<?php

use BookManager\App\App;
use Rabbit\Redirects\AdminNotice;


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
    
}