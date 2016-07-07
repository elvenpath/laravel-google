<?php

namespace App\Http\Controllers\Hoanghiep;

use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use \Hoanghiep\Googleapi\Hoanghiep\GoogleController;
use App\Google;

// ke thua lop xu ly
class UserinfoController extends GoogleController
{

    public function index(Request $request)
    {


        $client = App::make("Google_Client");
//        $this->handleProvider($request);

//        $token = $this->client_array;

        $email = "tranhuyle2013@gmail.com";

        $token = Google::select("access_token", "token_type", "expires_in", "created")->where("email", $email)->orderBy('id', 'desc')->first()->toJson();


        $refresh = Google::select("reset_numbers", "refresh_token")->where("email", $email)->orderBy('id', 'desc')->first();
        $number_refresh = $refresh["reset_numbers"];
        $refresh_token = $refresh["refresh_token"];

        $client->setAccessToken($token);
        if ($client->getAccessToken()) {
            try {
                // su dung the truy cap
                $google_oauth = App::make("Google_Service_Oauth2", [$client]);
                $user = $google_oauth->userinfo->get();
                dd($user);
            } catch (Google_Service_Exception $e) {
                echo $htmlBody = sprintf('<p>Thẻ truy cập đã hết hạn do ứng dụng hủy ủy quyền truy cập
 xem tại : https://security.google.com/settings/u/0/security/permissions?pli=1. <br/> mã thông báo lỗi : <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
                return redirect(route("google.redirect"));
            } catch (Google_Exception $e) {
                echo $htmlBody = sprintf('<p>Có lỗi xảy ra do cấu hình Client : <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }
        }

    }

}