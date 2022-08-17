@extends('layouts.main')

@section('content')

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMeter">
        Add new Tank Name
    </button>

    <modal>
        <tank></tank>
    </modal>
<br>
<br>

    <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>tankName</th>
        <th>created_at</th>
        <th>description</th>
        </thead>
        <tbody>
        @foreach( $allTypes as $row )
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->tankName}}</td>
                <td>{{$row->created_at}}</td>
                <td>{{$row->description}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('custom_scripts')
@stop



