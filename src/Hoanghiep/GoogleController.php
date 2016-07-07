<?php

namespace Hoanghiep\Googleapi\Hoanghiep;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use App\Google;
use Session;

class GoogleController extends Controller
{

    public $redirectToPath;

    public function RedirectProvider()
    {
        $client = App::make("Google_Client");
        $Oauth_uri = $client->createAuthUrl();
        return redirect($Oauth_uri);
    }



    // xử lý lưu trữ và chuyền hướng.
    // function chuyển hướng tới config/google.php redirectToPath và trả về session email và token

    public function handleProvider(Request $request)
    {
        $client = App::make("Google_Client");

        if (isset($request->code)) {
            // đổi mã ủy quyền lấy tehr truy cập
            $token = $client->authenticate($request->code);

            $accessToken = $client->getAccessToken();
            if (isset($accessToken)) {
                // kiểm tra thẻ truy cập lần 1
                if (isset($client->getAccessToken()['refresh_token'])) {
                    // sử dụng thẻ ủy quyền truy cập vào tài nguyên để lấy thông tin lưu trữ
                    $client->setAccessToken($accessToken);
                    $google_oauth = App::make("Google_Service_Oauth2", [$client]);
                    $user = $google_oauth->userinfo->get();
                    $email =  $user->email;
                    // kiểm tra người dùng hiện tại nếu đã tồn tại xóa đi
                    Google::where("email",$email)->delete();

                    // lưu thông tin thẻ ủy quyền của người dùng hiện tại và thời gian hết hạn và thẻ làm mới
                    Google::create([
                        "email" => $email,
                        "name" => $user->name,
                        "picture" => $user->picture,
                        "link" => $user->link,
                        "locale" => $user->locale,
                        "verified_email" => $user->verified_email,
                        "access_token" => $client->getAccessToken()["access_token"],
                        "token_type" => $client->getAccessToken()["token_type"],
                        "expires_in" => $client->getAccessToken()["expires_in"],
                        'id_token'=>$client->getAccessToken()["id_token"],
                        "reset_numbers" => 25,
                        "refresh_token" => $client->getAccessToken()["refresh_token"],
                        "created" => $client->getAccessToken()["created"]
                    ]);

                    $token = ["user"=>$user,"client"=>$token];
                    $redirectToPath= config("google.redirectToPath");
                    return redirect($redirectToPath)->with("user_token",$token);

                } else {
                    $client->setAccessToken($accessToken);
                    $google_oauth = App::make("Google_Service_Oauth2", [$client]);
                    $user = $google_oauth->userinfo->get();
                    $email =  $user->email;
                    Google::where("email",$email)->first()->update([
                        "access_token" => $client->getAccessToken()["access_token"],
                        "token_type" => $client->getAccessToken()["token_type"],
                        "expires_in" => $client->getAccessToken()["expires_in"],
                        'id_token'=>$client->getAccessToken()["id_token"],
                        "created" => $client->getAccessToken()["created"]
                    ]);

                    // number reset token
                    $number_reset = Google::select("reset_numbers")->where("email",$email)->first();
                    if(!empty($number_reset)){
                      $number =  $number_reset["reset_numbers"];
                        if($number === 1){
                            return redirect(route("google.redirect"));
                        }

                    }

                    $token = ["user"=>$user,"client"=>$token];
                    $redirectToPath= config("google.redirectToPath");
                    return redirect($redirectToPath)->with("user_token",$token);
                }
            }
        }
    }



    public function User_Auth(Request $request){
        $token = Session::get("user_token");
        if(!empty($token)){
            $user = $token["user"];
            $client = $token["client"];

            $name  = $user["name"];
            $email = $user["email"];
            $access_token = $client["access_token"];
            $expires_in = $client["expires_in"];

            return view("google::hoanghiep.google.auth",["name"=>$name,'email'=>$email,'access_token'=>$access_token,'expires_in'=>$expires_in]);
        }else {

                $success = "";
            return view("google::hoanghiep.google.auth",["success"=>$success]);
        }

    }


}
