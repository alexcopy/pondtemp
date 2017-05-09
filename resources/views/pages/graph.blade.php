@extends('layouts.main')
@section('custom_css')
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/css/c3.min.css">
@stop
@section('content')
    <div class="row">
        <input type="text" id="daterange"
               value="{{\Carbon\Carbon::now()->subDays(1)->format('d/m/Y g:i A') }} - {{ \Carbon\Carbon::now()->format('d/m/Y g:i A') }} "/>
    </div>
    <br/>
    <div class="row">
        <div class="chart">
            <div id="temp"></div>
        </div>
    </div>
    <br/>
    <br/>


    <div class="row">
        <div class="chart">
            <div id="humid"></div>
        </div>
    </div>

    <?php $dates = implode("\", \"", array_keys($shedAver))?>

    <script>


        var humid = {
            data: {
                x: 'x',
                columns: [
                    ['x', "{!! $dates !!}"],
                    ['Street Temp', {{ implode(", ", array_values($shedAver)) }}],
                    ['Pond Temp',{{ implode(", ", array_values($pondAver)) }}]
                ]
            },
            axis: {
                x: {
                    type: 'category' // this needed to load string x value
                }
            }
        };

    </script>


@stop
@section('custom_scripts')
    <script>
        $(document).ready(function () {

            var temps = c3.generate({
                bindto: '#temp',
                data: {
                    x: 'x',
                    columns: [
                        ['x', "{!! $dates !!}"],
                        ['Street Temp', {{ implode(", ", array_values($shedAver)) }}],
                        ['Pond Temp',{{ implode(", ", array_values($pondAver)) }}]
                    ]
                },
                axis: {
                    x: {
                        type: 'category' // this needed to load string x value
                    }
                }
            });
            var humid = c3.generate({
                bindto: '#humid',
                data: {
                    x: 'x',
                    columns: [
                        ['x', "{!! $dates !!}"],
                        ['Humidity', {{ implode(", ", array_values($humAver)) }}]
                    ]
                },
                axis: {
                    x: {
                        type: 'category'
                    }
                }
            });

            console.log("street: " + "{{ implode(" - ", array_values($shedAver)) }} ");
            console.log("pond:  " + "{{ implode(" - ", array_values($pondAver)) }} ");

            $('#daterange').daterangepicker({
                timePicker: true,
                dateLimit: {
                    "days": 60
                },
                timePickerIncrement: 20,
                locale: {
                    format: 'DD/MM/YYYY h:mm A'
                }
            }, function (start, end, label) {

                $.ajax({
                    method: "POST",
                    url: "/api/v3/getdate",
                    data: {startDate: start._d, endDate: end._d}
                })
                    .done(function (msg) {

                        setTimeout(function () {
                            msg.data.x.unshift('x');
                            msg.data.StreetTemp.unshift('Street Temp');
                            msg.data.PondTemp.unshift('Pond Temp');
                            msg.data.humid.unshift('Humidity')
                            temps.load({
                                columns: [
                                    msg.data.x,
                                    msg.data.StreetTemp,
                                    msg.data.PondTemp
                                ],
                                length: 0,
                                duration: 8500,
                            });
                            humid.load({
                                columns: [
                                    msg.data.x,
                                    msg.data.humid,
                                ],
                                length: 0,
                                duration: 8500,
                            });
                        });
                    })
                    .error(function (msg, status) {
                        alert(msg.statusText);
                    });

            });
        });
    </script>


    <script src="assets/js/d3-3.4.3.min.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/daterangepicker.js"></script>
    <script src="assets/js/c3.js"></script>
    {{--<script type="text/javascript" src="assets/js/graph.js"></script>--}}
@stop