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



    <script>
        var tempdata = {
//            data: {
//                xs: {
//                    'data1': 'x1',
//                    'data2': 'x2',
//                },
//                columns: [
//                    ['x1', 1, 3, 4, 5, 7, 10],
//                    ['x2', 3, 5, 7, 10, 12],
//                    ['data1', 3, 2, 1, 4, 15, 5],
//                    ['data2', 2, 18, 2, 10, 19]
//                ]
//            }

            data: {
                columns: [
                    ['Street Temp', {{ implode(", ", $weather->pluck('shed')->reverse()->flatten()->all()) }}],
                    ['Pond Temp',{{ implode(", ", $pondTemp->pluck('tempVal')->reverse()->flatten()->all()) }}]
                ]
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
