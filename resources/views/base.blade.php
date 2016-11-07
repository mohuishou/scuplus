<!DOCTYPE html>
<html lang="en" style="margin: 0;padding: 0;background: #efefef;width: 100%;height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SCUPLUS</title>
    @yield("style")

</head>
<body style="margin: 0;padding: 0;background: #efefef;width: 100%;height: 100%;">
<div id="main" style="max-width: 900px;margin: auto;">
    <div class="curved_box" style="display: inline-block;width: 100%;height: 100%;margin-top: 20px;padding: 20px;background-color: #fff;border: 1px solid #eee;-webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 60px rgba(0, 0, 0, 0.06) inset;-moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;position: relative;zoom: 1;">
        <div id="header">
        @if(isset($username))
        <h1>尊敬的{{$username}}</h1>
        @else
            <h2 style="text-align: center;margin-bottom: 50px;">尊敬的SCUPLUS用户</h2>
        @endif
        </div>
        <div id="content">
        @yield('content')
        </div>
        <div id="footer" style="color: #999;font-size: 13px;margin-top: 50px;">
            <div>
                <p style="color: #666;">SCUPLUS,一个非官方的川大功能号，送给每一个川大学子的福利</p>
                <p style="color: #666;">绩点/成绩/一键查询/订阅通知；考试提醒；课程表导出日历；图书超期提醒；短信/微信/邮箱/三位一体通知体系，每日自动更新</p>
                <p style="color: #666;">如需退订，请登录<a href="http://scuplus.cn/#!/user" style="text-decoration: none;color: #0099CC !important;font-size: 13px;">scuplus个人中心</a>，关闭消息服务</p>
            </div>
            <a href="http://scuplus.cn" style="text-decoration: none;color: #0099CC !important;font-size: 13px;">power by scuplus</a>
        </div>
    </div>

</div>
</body>
</html>
