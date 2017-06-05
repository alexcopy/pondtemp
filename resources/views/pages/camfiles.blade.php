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
                        <td><span class="alert-info badge">{{count($dirfile)}}</span></td>
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
                    <?php
                    $formatedDate = Carbon\Carbon::createFromTimestamp($date)->format('d/m H:i');
                    $state = (time() - $date) > 7200 ? 'ERR' : 'OK';
                    $stClass = $state == 'OK' ? "alert-success" : "alert-danger";
                    ?>

                    <tr>
                        <td>{{$folder}}</td>
                        <td><span class="{{$stClass}}">{{$state}}</span></td>
                        <td>{{$formatedDate}}</td>
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
                        <td><span class="alert-info badge">{{count($dirfile)}}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop