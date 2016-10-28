<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SCUPLUS</title>
    <style>
        html,body{
            margin:0;
            padding: 0;
            background: #efefef;
            width: 100%;
            height:100%;
        }
        a{
            text-decoration: none;
            color: #333;
        }
        a:hover{
            color: #99cccc;
        }
        #main{
            max-width: 900px;
            margin: auto;
        }
        /*--------纸张投影效果----------*/
        .curved_box {
            display: inline-block;
            width: 100%;
            height:100%;
            margin-top:20px;
            padding:20px;
            background-color: #fff;
            border-top: 2px solid #333244;
            /*border: 1px solid #eee;*/
            -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 60px rgba(0, 0, 0, 0.06) inset;
            -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;
            position: relative;
            zoom: 1;
        }
        #footer{
            color: #999;
            font-size: 13px;
        }
        #footer a{
            color: #999;
            font-size: 13px;
        }



        @media (max-width:500px) {
            #main{
                max-width:100%;
            }
            .curved_box {
                max-width:100%;
                margin-top: 0;
                padding: 20px 10px;
                height: 100%;
            }

        }
    </style>
    @yield("style")

</head>
<body>
<div id="main">
    <div  class="curved_box">
        <div id="header">
            @if(isset($username))
                <h1>尊敬的{{$username}}</h1>
            @else
                <h1>尊敬的SCUPLUS用户：</h1>
            @endif

            <p>您好!</p>
        </div>
        <div id="content">
            @yield('content')
        </div>
        <div id="footer">
            <div>
                <p>感谢使用scuplus，川大+，加你想要</p>
                <p>如需退订，请登录scuplus个人中心，关闭消息服务</p>
            </div>
            <a href="http://scuplus.cn">power by scuplus</a>
        </div>
    </div>

</div>
</body>
</html>
