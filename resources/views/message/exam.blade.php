@extends("base")
@section("title","成绩单")

@section("style")
    <style>

    </style>
@endsection

@section("content")
    <div>
        <p class="note" style="color: #666;margin-bottom: 50px;">您的考表更新了，或有即将参加的考试，点击查看<a href="http://scuplus.cn/#!/user" style="text-decoration: none;color: #0099CC !important;">scuplus</a></p>
        <table style="background: #fff;border: 1px solid #ccc;width: 95%;margin: 0 auto;padding: 0;border-collapse: collapse;border-spacing: 0;font-size: 12px;color: #333244;">
            <caption style="border: 1px solid #ddd;padding: 5px;background: #495c70;color: #fff;border-bottom: none;font-size: 16px;"> <b>您的考表</b>
            </caption>
            <thead>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">课程</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">日期</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">时间</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">地点</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">座位号</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">类型</th>
            </thead>
            <tbody>
            @foreach($args as $e)

                <tr style="border: 1px solid #ddd;padding: 5px;">
                  <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $e["class_name"]}}</td>
                  <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $e["date"]}}</td>
                  <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $e["time"]}}</td>
                  <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $e["campus"]}}-{{$e["building"]}}-{{$e["classroom"]}}</td>
                  <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $e["seat"]}}</td>
                  <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $e["exam_name"]}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
