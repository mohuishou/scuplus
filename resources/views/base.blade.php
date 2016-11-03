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
            color: #0099CC !important;
        }
        a:hover{
            color: #336699 !important;
        }
        p{
            color: #666;
        }

        #header h2{
            text-align: center;
            margin-bottom: 50px;
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
            border: 1px solid #eee;
            -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 60px rgba(0, 0, 0, 0.06) inset;
            -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;
            position: relative;
            zoom: 1;
        }

        .note{
            color: #666;
            margin-bottom:50px;
        }
        #footer{
            color: #999;
            font-size: 13px;
            margin-top:50px;
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

        table {
            background: #fff;
            border: 1px solid #ccc;
            width: 95%;
            padding: 0;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 auto;
            font-size: 12px;
            color: #333244;
        }
        table caption,
        table tr {
            border: 1px solid #ddd;
            padding: 5px;
        }
        table td,
        table th {
            padding: 5px 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        table caption {
            background: #495c70;
            color: #fff;
            border-bottom: none;
            font-size: 16px;
        }

        table .title {
            border: 1px solid #ddd;
        }
    </style>
    @yield("style")

</head>
<body>
<div id="main">
    <div  class="curved_box">
        <div id="header">
            @if(isset($username))
                <h2>尊敬的{{$username}}</h2>
            @else
                <h2>尊敬的SCUPLUS用户：</h2>
            @endif
        </div>
        <div id="content">
            @yield('content')
        </div>
        <div id="footer">
            <div>
                <p>SCUPLUS,一个非官方的川大功能号，送给每一个川大学子的福利</p>
                <p>绩点/成绩/一键查询/订阅通知；考试提醒；课程表导出日历；图书超期提醒；短信/微信/邮箱/三位一体通知体系，每日自动更新</p>
                <p>如需退订，请登录<a href="http://scuplus.cn/!#/user">scuplus个人中心</a>，关闭消息服务</p>
            </div>
            <a href="http://scuplus.cn">power by scuplus</a>
        </div>
    </div>

</div>
</body>
</html>
