@extends("base")
@section("title","成绩单")

@section("style")
    <style>

    </style>
@endsection

@section("content")
    <div>
        <p>您的成绩单有更新！</p>
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