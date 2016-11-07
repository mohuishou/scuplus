@extends("base")
@section("title","图书超期提醒")

@section("style")
    <style>

    </style>
@endsection

@section("content")
    <div>
        <p class="note" style="color: #666;margin-bottom: 50px;">您的下列图书即将到期，详情请登录 <a href="http://scuplus.cn/#!/user" style="text-decoration: none;color: #0099CC !important;">scuplus</a></p>
        <table style="background: #fff;border: 1px solid #ccc;width: 95%;margin: 0 auto;padding: 0;border-collapse: collapse;border-spacing: 0;font-size: 12px;color: #333244;">
            <caption style="border: 1px solid #ddd;padding: 5px;background: #495c70;color: #fff;border-bottom: none;font-size: 16px;"> <b>即将超期的图书</b>
            </caption>
            <thead>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">书籍</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">作者</th>
            <th style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;text-transform: uppercase;font-size: 14px;letter-spacing: 1px;">到期时间</th>
            </thead>
            <tbody>
            @foreach($args as $lib)
            <tr style="border: 1px solid #ddd;padding: 5px;">
                <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $lib["title"] }}</td>
                <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $lib["author"] }}</td>
                <td style="padding: 5px 8px;text-align: center;border: 1px solid #ddd;">{{ $lib["end_day"] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection