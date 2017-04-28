@extends('layouts.main')
@section('content')
<h3>Temp Results</h3>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>readingDate</th>
                    <th>pond</th>
                    <th>str</th>
                    <th>strHum</th>
                </tr>
                </thead>
                <tbody>
                @foreach($weather as $row)
                    <tr>
                        <td>{{$row->readingDate}}</td>
                        <td>{{$row->pond}}</td>
                        <td>{{$row->shed}}</td>
                        <td>{{$row->shedhumid}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>readingDate</th>
                    <th>pond</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tempReader as $row)
                    <tr>
                        <td>{{$row->readingDate}}</td>
                        <td>{{$row->tempVal}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>readingDate</th>
                    <th>pondUp</th>
                    <th>fl3</th>
                </tr>
                </thead>
                <tbody>
                @foreach($guages as $row)
                    <tr>
                        <td>{{$row->readingDate}}</td>
                        <td>{{$row->pondUpper}}</td>
                        <td>{{$row->fl3}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop