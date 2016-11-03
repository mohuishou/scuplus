@extends("base")
@section("title","成绩单")

@section("style")
    <style>

    </style>
@endsection

@section("content")
    <div>
        <p class="note">您的成绩单有更新,查看详细成绩/绩点，请点击登录 <a href="http://scuplus.cn/#!/grade">scuplus</a></p>
        <caption> <b>已更新的成绩</b></caption>
        <table>
            <thead>
                <th>课程</th>
                <th>成绩</th>
                <th>学分</th>
                <th>类型</th>
            </thead>
            <tbody>
                @foreach($data as $g)
                    <tr>
                        <td>{{ $g.name }}</td>
                        <td>{{ $g.grade }}</td>
                        <td>{{ $g.credit }}</td>
                        <td>{{ $g.courseType }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection