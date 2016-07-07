<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Google extends Model {

    protected $table = "googles";
    protected $fillable = [
        "email", "name", "picture", "link", "locale", "verified_email", 'access_token', 'token_type', 'expires_in','id_token', 'refresh_token', 'created'
    ];

    /**
     * Chan ko cho insert hay select. dung $guarded
     *
     * @var array
     */
//    protected $guarded = [  ];

    /**
     * An bot ket qua o select 
     *
     * @var array
     */
//    protected $hidden = [
//        'email', 'name', 'picture', 'link', 'locale', 'verified_email',"reset_numbers","status"
//    ];

}
