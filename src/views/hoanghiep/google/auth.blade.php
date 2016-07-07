<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
            width: 960px;
        }

        .title {
            font-size: 26px;
        }
        .p {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">
            @if(isset($success))
               <h3 class="p">Bạn đã ủy quyền và có thể truy cập ứng dụng.</h3>
                @else
                <p>Tên của bạn <span class="p">{{ $name }}</span></p>
                <p>Email của bạn <span class="p"> {{ $email }}</span></p>
                <p>Thẻ truy cập của bạn <span class="p"> {{ $access_token }} </span></p>
                <p>Thời gian sử dụng  <span class="p"> {{ $expires_in }} </span></p>
                @endif

        </div>
    </div>
</div>
</body>
</html>
