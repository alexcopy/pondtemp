@extends('layouts.main')
@section('custom_css')
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/css/c3.min.css">
@stop
@section('content')
    <div class="row">
        <input type="text" id="daterange" value="01/01/2015 - 01/31/2015"/>
    </div>
    <br/>
    <div class="row">
        <div class="chart">
            <div id="chart"></div>
        </div>
    </div>

<?php $dates= implode("\", \"", array_keys($shedAver))?>

    <script>
        var tempdata = {

            data: {
                x: 'x',
                columns: [
                    ['x',   "{!! $dates !!}" ],
                    ['Street Temp', {{ implode(", ", array_values($shedAver)) }}],
                    ['Pond Temp',{{ implode(", ", array_values($pondAver)) }}]
                ]
            },
            axis: {
                x: {
                    type: 'category' // this needed to load string x value
                }
            }
        }
    </script>


@stop
@section('custom_scripts')
    <script>
        $(document).ready(function () {
            var chart = c3.generate(tempdata);

            $('#daterange').daterangepicker({
                "autoApply": true,
                "startDate": "04/01/2017",
                "endDate": "04/21/2017"
            }, function (start, end, label) {
            });
        });
    </script>


    <script src="assets/js/d3-3.4.3.min.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/daterangepicker.js"></script>
    <script src="assets/js/c3.js"></script>
    {{--<script type="text/javascript" src="assets/js/graph.js"></script>--}}
@stop
