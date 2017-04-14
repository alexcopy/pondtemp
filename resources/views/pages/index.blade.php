@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-5">
            <table class="table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>readingDate</th>
                    <th>pond</th>
                    <th>shed</th>
                    <th>shedhumid</th>
                    <th>timestamp</th>
                </tr>
                </thead>
                <tbody>
                @foreach($weather as $row)
                    <tr>
                        <td>{{$row->readingDate}}</td>
                        <td>{{$row->pond}}</td>
                        <td>{{$row->shed}}</td>
                        <td>{{$row->shedhumid}}</td>
                        <td>{{$row->timestamp}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop