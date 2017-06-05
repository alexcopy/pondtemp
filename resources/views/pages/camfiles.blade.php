@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <h5>Today results:</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>cam name</th>
                    <th>q-ty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dirFiles['files'] as $folder=> $dirfile)
                    <tr>
                        <td>{{$folder}}</td>
                        <td>{{count($dirfile)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <h5>Status:</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>cam name</th>
                    <th>is OK</th>
                    <th>last alarm</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dirFiles['changed'] as $folder=> $date)
                    <tr>
                        <td>{{$folder}}</td>
                        <td>{{$date}}</td>
                        <td>{{$date}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <h5>All dirs results:</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>cam name</th>
                    <th>q-ty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dirFiles['dirs'] as $folder=> $dirfile)
                    <tr>
                        <td>{{$folder}}</td>
                        <td>{{count($dirfile)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop