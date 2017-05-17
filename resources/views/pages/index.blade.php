@extends('layouts.main')
@section('content')
<h3>Temp Results</h3>
    <div class="row">
        <div class="col-md-5">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>readingDate</th>
                    <th>pondT</th>
                    <th>strTemp</th>
                    <th>strHum</th>
                    <th>shedHum</th>
                    <th>pressure</th>
                </tr>
                </thead>
                <tbody>
                @foreach($weather as $row)
                    <tr>
                        <td>{{$row->readingDate}}</td>
                        <td>{{$row->pond}}</td>
                        <td>{{$row->streettemp}}</td>
                        <td>{{$row->streethumid}}</td>
                        <td>{{$row->shedhumid}}</td>
                        <td>{{$row->pressure}}</td>

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