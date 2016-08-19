<style>
    #main {
        max-width:900px;
    }

    .btn {
        color: rgb(255, 255, 255);
        font-size: 15px;
        max-width:200px;
        padding-top: 14px;
        padding-bottom:14px;
        padding-left: 25px;
        padding-right:25px;
        border-width: 0px;
        border-color: rgb(197, 229, 145);
        border-style:outset;
        border-radius: 8px;
        background-color: rgb(120, 195, 0);
        cursor: hand;
    }

    .btn:hover {
        color: #692369;
        background-color: #78c300;
        border-color: #c5e591;
    }
</style>
<div id="main">
    <p>尊敬的用户 {{ $username }} ：</p>
    <p>您好！您正在申请重置密码，请点击一下链接重置</p>
    <a class="btn" style="text-align: center;display: block;" href="{{ $verify_url }}">点击验证</a>
    <br/>
    <p>如果链接无法点击，请将下方链接地址复制到浏览器地址栏谢谢：</p>
    <a href="{{ $verify_url }}"><br/>{{ $verify_url }}</a>
    <p style="text-align: right;margin-right: 50px;">——PowerBy Scuplus</p>
</div>
