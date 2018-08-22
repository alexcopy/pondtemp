@extends('layouts.main')
@section('custom_css')
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/css/c3.min.css">
@stop

@section('content')
    <div >
        <example-component></example-component>
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
        <div class="row">

        </div>
@stop

@section('custom_scripts')
    <script src="assets/js/d3-3.4.3.min.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/daterangepicker.js"></script>
    <script src="assets/js/c3.js"></script>
    <script type="text/javascript" src="assets/js/files.js"></script>
@stop