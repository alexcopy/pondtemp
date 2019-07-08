@extends('layouts.main')

@section('content')

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addMeter">
        Add New Readings
    </button>
    <br>
    <br>
    <modal>
        <stats>
        </stats>
    </modal>

    <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>meter</th>
        <th>read</th>
        <th>litters</th>
        <th>speed</th>
        <th>data</th>
        </thead>
        <tbody>
        @foreach( $allValues->reverse() as $readingsRow )
            <tr>
                <td>{{$readingsRow->id}}</td>
                <td>{{$readingsRow->meter_id}}</td>
                <td>{{$readingsRow->readings}}</td>
                <td>{{$readingsRow->diff}}</td>
                <td> <span class="text-danger">{{$readingsRow->perHour}} l/h</span></td>
                <td>{{$readingsRow->created_at}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
@stop
@section('custom_scripts')
@stop
