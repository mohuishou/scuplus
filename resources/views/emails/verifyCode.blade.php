@extends("base")
@section("title","验证码")
@section("style")
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
            text-decoration: none;
        }

        .btn:hover {
            color: #692369;
            background-color: #78c300;
            border-color: #c5e591;
        }

        #verify{
            font-size: 30px;
            font-weight: bold;
        }
    </style>
@endsection
@section("content")
    <div>
        <p>您的验证码如下（2小时内有效）：</p>
        <a id="verify" class="btn" style="text-align: center;display: block;" href="#">{{$verify_code}}</a>
    </div>
@endsection