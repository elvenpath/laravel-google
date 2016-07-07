<?php

Route::get("social/google/redirect", ['middleware' => 'web',"as" => "google.redirect", "uses" => "Hoanghiep\Googleapi\Hoanghiep\GoogleController@RedirectProvider"]);

Route::get("social/google/handle/{provide?}", ['middleware' => 'web', "as" => "google.handle", "uses" => "Hoanghiep\Googleapi\Hoanghiep\GoogleController@handleProvider"]);

Route::get("social/google/Auth", ['middleware' => 'web',"as" => "google.auth", "uses" => "Hoanghiep\Googleapi\Hoanghiep\GoogleController@User_Auth"]);


// thêm những đường dẫn sau vào routes.php của bạn tại app/Http/routes.php


//// Session này chỉ dùng được 1 lần.
//Route::get('social/google/user_info',["middleware"=>"web","uses"=>"Hoanghiep\UserinfoController@index"]);
//

//
//Route::get('social/google/youtube/add_subcription',["as"=>"get_add_subcription","middleware"=>"web","uses"=>"Hoanghiep\YoutubeController@get_add_subcription"]);
//Route::post('social/google/youtube/add_subcription',["as"=>"post_add_subcription","middleware"=>"web","uses"=>"Hoanghiep\YoutubeController@post_add_subcription"]);