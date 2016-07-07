# Hoàng Hiệp API Google Client in Laravel Master

# Chuẩn Bị 

Vào ứng dụng laravel vào file composer.json thêm dòng  :

 "minimum-stability": "dev",

Để sửa lỗi

at any version for your minimum-stability (stable). Check the package spelling or your minimum-stability

sau đó mở composer chạy lệnh tải gói

composer require hoanghiep/laravel-google-api


Sau khi tải gói lưu ý chuẩn bị :

+ bật apache rewrite_module

Sửa lỗi cURL error 60: SSL :

tải file https://curl.haxx.se/ca/cacert.pem

để vào thư mục php của bạn

sau đó sửa file php.ini

tìm dòng ;curl.cainfo sửa thành

curl.cainfo= "path/cacert.pem"

Lưu ý các phiên bản sử dụng có thể ko sửa được do phiên bản apache ví dụ dùng wampp 5.5.12 ko được nhưng bản  5.6.12 lại được.





# 1. cấu hình database.

cấu hình cơ sở dữ liệu trong tập tin .env

# 2. thêm provider vào config/app.php

Hoanghiep\Googleapi\GoogleClientProvider::class

# 3. Chạy lệnh xuất những file cần thiết

php artisan vendor:publish

Các file được tạo ra gồm

- file tạo database lưu trữ trong database\migrations\2016_07_03_010808_create_googles_table.php
- file config dòng chảy xác thực trong config/google.php
- file làm việc với model trong app\Google.php
- file mẫu các dịch vụ google sẽ sử dụng trong app\Providers\GoogleServiceProvider.php
- file mẫu controller sử dụng gọi server và lấy mã ủy quyền và sử dụng thư viện trong app\Http\Controllers\Hoanghiep\UserinfoController.php

# 4. Cấu hình dòng chảy tới máy chủ google trong config/google.php

+ AppName => tên ứng dụng
+ AppKey => khóa API truy cập dữ liệu công cộng
+ client_id => khóa client id máy chủ ủy quyền
+ client_secret => khóa lựa chọn máy chủ ủy quyền
+ redirect => thay thế bằng url dụng http://localhost/{projiect-name}/social/google/handle/token => yêu cầu trong máy chủ bắt buộc có url này để gói xử lý.
+ scopes=> phạm vi cần yêu cầu người dùng đồng ý để có thể truy cập dữ liệu
+ redirectToPath => sau khi sử lý thành công url sẽ được chuyển hướng tới trang bạn cần chuyển demo là trang /test
và controller xử lý là UserinfoController@index


# 5. Chạy lệnh tạo bảng dữ liệu

php artisan migrate

# 6. Chạy thử dòng chảy

localhost/project-name/social/google/redirect

# 7. Thấy báo lỗi không tìm thấy route url /test

NotFoundHttpException in RouteCollection.php line 161:


sửa khai báo url như sau :

vào app/Http/routes.php

thêm định nghĩa url /test gọi đến UserinfoController@index

Route::get('/test',["middleware"=>"web","uses"=>"Hoanghiep\UserinfoController@index"]);


nếu ko dùng user /test định nghĩa trong file config/google redirectPath bạn dùng home chẳng hạn thì nó sẽ như này

Route::get('/home',["middleware"=>"web","uses"=>"Hoanghiep\UserinfoController@index"]);

# 8. Xem kết quả và dựa vào controller làm mẫu để khởi tạo controller khác muốn sử dụng thẻ ủy quyền và truy cập dữ liệu của google api.

# 9  Extension kế thừa class GoogleController

use \Hoanghiep\Googleapi\hoanghiep\GoogleController;

// ke thua lop xu ly
class UserinfoController extends GoogleController {

}

+  config google client

$client = App::make("Google_Client");

+ handle Token  "xử lý token"

$this->handleProvider($request);

+ getToken  "lấy token và số lần làm mới thẻ"

 $token = $this->client_array;

+ Check Token

 if (!isset($token[0])):
            return redirect()->route("google.redirect");
        endif;
+ get refresh_Token "lấy số lần làm mới"
        $number_refresh = $token[0];
+ Check Token "kiểm tra nếu còn 1 thì làm yêu cầu  truy cập lại "
        if ($number_refresh == 1):
            return redirect()->route("google.redirect");
+ getAccessToken "lấy thẻ truy cập"
        $accessToken = $token[1];

+ setAccessToken "Sử dụng thẻ truy cập"
        $client->setAccessToken($accessToken);

+ gọi api đã khai báo trong và truyền vào $client hiện  tại
        $google_oauth = App::make("Google_Service_Oauth2", [$client]);
        $user = $google_oauth->userinfo->get();
+ hiển thị kết quả

       dd($user);

# Sử dụng Class Controller Khác Và Provider Service  Khác ví dụ Youtube

1. Bật các API youtube service
2. Vào config/app.php thêm provider

     App\Providers\GoogleServiceProvider::class,
3. Thêm các class dịch vụ cần sử dụng ví dụ
// ở trên class
use Google_Service_Youtube;
// thêm một class mới vào container service laravel trong method register(){

   $this->app->bind("Google_Service_Youtube", function ($app, array $client) {
            return new Google_Service_Youtube($client[0]);
        });

  }

4. Cấu hình thêm ủy quyền mới phạm vi truy cập dữ liệu trong config/google.php chú ý bật các Api cần sử dụng
3. Khởi tạo route

Route::get('social/google/youtube',["middleware"=>"web","uses"=>"Hoanghiep\YoutubeController@index"]);

4. Chạy lệnh tạo controller :

php artisan make:controller Hoanghiep\YoutubeController

5. Vào file controller YoutubeController sử dụng lớp kế thừa GoogleClientController tương tự UserinfoController.php

trông như thế này :

<?php

namespace App\Http\Controllers\Hoanghiep;

use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use \Hoanghiep\Googleapi\Hoanghiep\GoogleController;

class YoutubeController extends GoogleController
{
    // code
}


6. trong phần code có các tác vụ gọi tới máy chủ lấy thẻ ủy quyền và sử dụng thẻ ủy quyền tương tự.

 public function index(Request $request) {
        $client = App::make("Google_Client");
        $this->handleProvider($request);
        $token = $this->client_array;
        if (!isset($token[0])):
            return redirect()->route("google.redirect");
        endif;
        $number_refresh = $token[0];
        if ($number_refresh == 1):
            return redirect()->route("google.redirect");
        endif;
        $accessToken = $token[1];
        $client->setAccessToken($accessToken);


        $youtube = App::make("Google_Service_Youtube",[$client]);

        dd($youtube);

  }


  7.   khi chạy url :

  http://localhost/{project-name}/social/google/youtube

  Kết quả :

  Google_Service_YouTube {#193 ▼
    +activities: Google_Service_YouTube_Resource_Activities {#187 ▶}
    +captions: Google_Service_YouTube_Resource_Captions {#192 ▶}
    +channelBanners: Google_Service_YouTube_Resource_ChannelBanners {#182 ▶}
    +channelSections: Google_Service_YouTube_Resource_ChannelSections {#190 ▶}
    +channels: Google_Service_YouTube_Resource_Channels {#213 ▶}
    +commentThreads: Google_Service_YouTube_Resource_CommentThreads {#212 ▶}
    +comments: Google_Service_YouTube_Resource_Comments {#211 ▶}
    +fanFundingEvents: Google_Service_YouTube_Resource_FanFundingEvents {#210 ▶}
    +guideCategories: Google_Service_YouTube_Resource_GuideCategories {#200 ▶}
    +i18nLanguages: Google_Service_YouTube_Resource_I18nLanguages {#199 ▶}
    +i18nRegions: Google_Service_YouTube_Resource_I18nRegions {#198 ▶}
    +liveBroadcasts: Google_Service_YouTube_Resource_LiveBroadcasts {#196 ▶}
    +liveChatBans: Google_Service_YouTube_Resource_LiveChatBans {#197 ▶}
    +liveChatMessages: Google_Service_YouTube_Resource_LiveChatMessages {#194 ▶}
    +liveChatModerators: Google_Service_YouTube_Resource_LiveChatModerators {#195 ▶}
    +liveStreams: Google_Service_YouTube_Resource_LiveStreams {#204 ▶}
    +playlistItems: Google_Service_YouTube_Resource_PlaylistItems {#203 ▶}
    +playlists: Google_Service_YouTube_Resource_Playlists {#202 ▶}
    +search: Google_Service_YouTube_Resource_Search {#201 ▶}
    +sponsors: Google_Service_YouTube_Resource_Sponsors {#205 ▶}
    +subscriptions: Google_Service_YouTube_Resource_Subscriptions {#188 ▶}
    +thumbnails: Google_Service_YouTube_Resource_Thumbnails {#189 ▶}
    +videoAbuseReportReasons: Google_Service_YouTube_Resource_VideoAbuseReportReasons {#181 ▶}
    +videoCategories: Google_Service_YouTube_Resource_VideoCategories {#216 ▶}
    +videos: Google_Service_YouTube_Resource_Videos {#206 ▶}
    +watermarks: Google_Service_YouTube_Resource_Watermarks {#218 ▶}
    +batchPath: null
    +rootUrl: "https://www.googleapis.com/"
    +version: "v3"
    +servicePath: "youtube/v3/"
    +availableScopes: null
    +resource: null
    -client: Google_Client {#179 ▶}
    +"serviceName": "youtube"
  }


