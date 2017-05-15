@extends('layouts.main')
@section('custom_css')
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/css/c3.min.css">
@stop
@section('subtabs')

    <div class="row">
        <div class="col-xs-3 pull-left">
            <input type="text" id="daterange" class="form-control"
                   value="{{\Carbon\Carbon::yesterday()->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} "/>
        </div>
        <div class="col-xs-2 pull-right">
            <h5 class="text-success">Current Str: {{$shed}}.0 &deg;C</h5>
            <h5 class="text-primary">Current Pnd: {{$pond}} &deg;C</h5>
        </div>

    </div>
@stop

@section('content')
    <div class="row">
        <div class="chart">
            <div id="temp"></div>
        </div>
    </div>
    <br/>

    <div class="row">
        <div class="chart">
            <div id="humid"></div>
        </div>
    </div>




@stop
@section('custom_scripts')



    <script src="assets/js/d3-3.4.3.min.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/daterangepicker.js"></script>
    <script src="assets/js/c3.js"></script>
    <script type="text/javascript" src="assets/js/graph.js"></script>
@stop