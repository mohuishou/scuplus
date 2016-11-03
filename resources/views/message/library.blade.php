@extends("base")
@section("title","图书超期提醒")

@section("style")
    <style>

    </style>
@endsection

@section("content")
    <div>
        <p class="note">您有图书即将超期，一键续借，请点击登录 <a href="http://scuplus.cn/#!/user">scuplus</a></p>
        <table>
            <caption> <b>即将超期的图书</b></caption>
            <thead>
                <th>书籍</th>
                <th>作者</th>
                <th>到期时间</th>
            </thead>
            <tbody>
                @foreach($data as $lib)
                    <tr>
                        <td>{{ $lib.title }}</td>
                        <td>{{ $lib.author }}</td>
                        <td>{{ $lib.end_day }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection