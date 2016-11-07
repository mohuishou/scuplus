@extends("base")
@section("title","成绩单")

@section("style")
    <style>

    </style>
@endsection

@section("content")
      <div>
        <p class="note" style="color: #666;margin-bottom: 50px;">您的成绩单有更新,查看详细成绩/绩点，请点击登录 <a href="http://scuplus.cn/#!/grade" style="text-decoration: none;color: #0099CC !important;">scuplus</a></p>
        <table style="background: #fff;border: 1px solid #ccc;width: 95%;margin: 0 auto;padding: 0;border-collapse: collapse;border-spacing: 0;font-size: 12px;color: #333244;">
            <caption style="border: 1px solid #ddd;padding: 5px;background: #495c70;color: #fff;border-bottom: none;font-size: 16px;"> <b>已更新的成绩</b>
            </caption>
            <thead>
              <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">课程</th>
              <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">成绩</th>
              <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">学分</th>
              <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">类型</th>

            </thead>
            <tbody>
            @foreach($args as $g)
            <tr style="border: 1px solid #ddd;padding: 5px;">
              <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $g["name"] }}</td>
              <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $g["grade"] }}</td>
              <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $g["credit"] }}</td>
              <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $g["courseType"]}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
