<?php
namespace App\Http\Controllers\Hoanghiep;

use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use \Hoanghiep\Googleapi\Hoanghiep\GoogleController;
use Google_Service_YouTube_ResourceId;
use Google_Service_YouTube_SubscriptionSnippet;
use Google_Service_YouTube_Subscription;
use App\Google;
use Google_Service_Exception;


class YoutubeController extends GoogleController
{

    public function get_add_subcription(){
        return view("google.youtube.add_subcription");
    }

    public function post_add_subcription(Request $request) {

        // request -> channel_id = id kênh cần đăng ký mới . channel_id là tham số truyền lên từ post
        if(isset($request->channel_id)){
            $ID_ChANNEL = $request->channel_id;
        }else {
            $ID_ChANNEL = "UCtVd0c0tGXuTSbU5d8cSBUg";
        }



        $client = App::make("Google_Client");
//        $this->handleProvider($request);

//        $token = $this->client_array;

        $email = "tranhuyle2013@gmail.com";

        $token = Google::select("access_token","token_type","expires_in","created")->where("email",$email)->orderBy('id', 'desc')->first()->toJson();


        $refresh = Google::select("reset_numbers","refresh_token")->where("email",$email)->orderBy('id', 'desc')->first();
        $number_refresh = $refresh["reset_numbers"];
        $refresh_token= $refresh["refresh_token"];

        $client->setAccessToken($token);
        if ($client->getAccessToken()) {
            try {

                if ($client->isAccessTokenExpired()) {
                    $client->refreshToken($refresh_token);
                    $new_number_refresh = $number_refresh - 1;
                    $number_refresh = Google::select("reset_numbers")->where("refresh_token", $refresh_token)->update([
                        "reset_numbers" => $new_number_refresh
                    ]);
                }


                $youtube = App::make("Google_Service_Youtube",[$client]);
                $resourceId = new Google_Service_YouTube_ResourceId();
                $resourceId->setChannelId($ID_ChANNEL);
                $resourceId->setKind('youtube#channel');

                // Tạo một đối tượng phan và thiết lập ID tài nguyên của nó .
                // Create a snippet object and set its resource ID.
                $subscriptionSnippet = new Google_Service_YouTube_SubscriptionSnippet();
                $subscriptionSnippet->setResourceId($resourceId);

                // Tạo một yêu cầu đăng ký có chứa các đối tượng đoạn .
                // Create a subscription request that contains the snippet object.
                $subscription = new Google_Service_YouTube_Subscription();
                $subscription->setSnippet($subscriptionSnippet);


                // Thực hiện các yêu cầu và trả về một đối tượng thông tin chứa về đăng ký mới .
                // Execute the request and return an object containing information
                // about the new subscription.
                $subscriptionResponse = $youtube->subscriptions->insert('id,snippet',
                    $subscription, array());



                $htmlBody = "<h3>Bạn đã thêm một kênh mới</h3>";
                $htmlBody .= "<h3>Đăng ký kênh </h3><ul>";
                $htmlBody .= sprintf('<li>%s (%s)</li>',
                    $subscriptionResponse['snippet']['title'],
                    $subscriptionResponse['id']);
                $htmlBody .= '</ul>';
                return  $htmlBody;

            } catch (Google_Service_Exception $e) {
               echo $htmlBody = sprintf('<p>Thẻ truy cập đã hết hạn do ứng dụng hủy ủy quyền truy cập
 xem tại : https://security.google.com/settings/u/0/security/permissions?pli=1. <br/> mã thông báo lỗi : <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
             return   redirect(route("google.redirect"));
            } catch (Google_Exception $e) {
                echo   $htmlBody = sprintf('<p>Có lỗi xảy ra do cấu hình Client : <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }

        }



    }
}
