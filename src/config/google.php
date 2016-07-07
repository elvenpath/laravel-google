<?php

return [
    'AppName' => "Google API YOUR",
    'AppKey' => "AIzaSyB5Ph6LlcBuY5yHFTe71lXqDDeYzjqyeGU",
    'client_id' => "531035095190-kgicutfkimd78oauri51ocu9b0pvdh8m.apps.googleusercontent.com",
    'client_secret' => "1zctXXtMZRlc55NC1Z9hEyB8",
    'redirect' => "http://localhost/laravel/social/google/handle/token",
    'scopes' => [
        'https://www.googleapis.com/auth/plus.login',
        'https://www.googleapis.com/auth/plus.me',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/youtube',
        'https://www.googleapis.com/auth/youtube.force-ssl',
        'https://www.googleapis.com/auth/youtube.readonly',
        'https://www.googleapis.com/auth/youtube.upload',
        'https://www.googleapis.com/auth/youtubepartner',
        'https://www.googleapis.com/auth/youtubepartner-channel-audit',
        'https://www.googleapis.com/auth/yt-analytics-monetary.readonly',
        'https://www.googleapis.com/auth/yt-analytics.readonly',
        'https://www.googleapis.com/auth/yt-analytics-monetary.readonly',
        'https://www.googleapis.com/auth/yt-analytics.readonly'],


        // redirectToPath sau khi ủy quyền
        'redirectToPath'=>'social/google/Auth'
        ]

;
