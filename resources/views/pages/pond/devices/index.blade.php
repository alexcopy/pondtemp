@extends('layouts.main')

@section('content')

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMeter">
        Add New Device
    </button>

    <modal>
        <device></device>
    </modal>
    <br>
    <br>
    <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>pond_id</th>
        <th>type_id</th>
        <th>deviceName</th>
        <th>created_at</th>
        <th>description</th>
        </thead>
        <tbody>
        @foreach( $allTypes as $row )
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->pond_id}}</td>
                <td>{{$row->type_id}}</td>
                <td>{{$row->deviceName}}</td>
                <td>{{$row->created_at}}</td>
                <td>{{$row->description}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('custom_scripts')
@stop



