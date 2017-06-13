@extends('layouts.main')
@section('custom_css')
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/css/c3.min.css">
@stop

@include('helpers.functions')
@section('content')

    <div class="row">
        <div class="col-sm-3">
            <h5>{{title_case('today\'s results')}}</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>cam name</th>
                    <th>q-ty</th>
                    <th>size</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dirFiles['files'] as $folder=> $dirfile)
                    <tr>
                        @php
                            $href='allfiles/details'.'?'.http_build_query(['q'=>'showtoday', 'folder'=>$folder, 'limit'=>500]);
                        @endphp
                        <td><strong><a href="{{$href}}"> {{$folder}}</a> </strong></td>
                        <td><span class="alert-info badge">{{count($dirfile)}}</span></td>
                        <td>{{$dirFiles['size'][$folder]}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <h5>{{title_case('Status')}}</h5>
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
                    @php
                        $formatedDate = Carbon\Carbon::createFromTimestamp($date)->format('d/m H:i');
                        $state = (time() - $date) > 7200 ? 'ERR' : 'OK';
                        $stClass = $state == 'OK' ? "alert-success" : "alert-danger";
                    @endphp

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
            <h5>{{title_case('All dirs results:')}}</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>cam name</th>
                    <th>q-ty</th>
                    <th>size</th>
                </tr>
                </thead>
                <tbody>

                @foreach($dirFiles['dirs'] as $folder=> $dirfile)
                    @php
                        $href='allfiles/details'.'?'.http_build_query(['q'=>'showfolders', 'folder'=>$folder, 'limit'=>500]);
                    @endphp
                    <tr>
                        <td><strong><a href="{{$href}}"> {{$folder}}</a> </strong></td>
                        <td><span class="alert-info badge">{{count($dirfile)-1}}</span></td>
                        <td>{{getSize($ftpDir.'/'.$folder)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <br/>
    <br/>
    <br/>
    <div class="row">
        <div class="chart">
            <div id="qty"></div>
        </div>
    </div>
    <br>
    <br>

    <h4>Time Line</h4>

    <div class="row">
        <div class="col-sm-3">
            <table id="filestimeline" class="table table-responsive table-bordered">

            </table>
        </div>
    </div>
@stop

@section('custom_scripts')
    <script src="assets/js/d3-3.4.3.min.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/daterangepicker.js"></script>
    <script src="assets/js/c3.js"></script>
    <script type="text/javascript" src="assets/js/files.js"></script>
@stop