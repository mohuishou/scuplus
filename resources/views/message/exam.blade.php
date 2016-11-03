@extends("base")
@section("title","成绩单")

@section("style")
    <style>

    </style>
@endsection

@section("content")
    <div>
        <p class="note">您的考表更新了，或有即将参加的考试，点击查看 <a href="http://scuplus.cn/#!/user">scuplus</a></p>
        <table>
            <caption> <b>考表</b></caption>
            <thead>
                <th>课程</th>
                <th>日期</th>
                <th>时间</th>
                <th>地点</th>
                <th>座位号</th>
                <th>类型</th>
            </thead>
            <tbody>
                @foreach($data as $e)
                    <tr>
                        <td>{{ $e.class_name}}</td>
                        <td>{{ $e.date }}</td>
                        <td>{{ $e.time }}</td>
                        <td>{{ $e.campus}}-{{$e.building}}-{{$e.classroom}}</td>
                        <td>{{ $e.seat}}</td>
                        <td>{{ $e.exam_name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection