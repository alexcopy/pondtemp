@extends('layouts.main')

@section('content')

        <stats></stats>

    <br>
    <br>



    <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>meter</th>
        <th>read</th>
        <th>litters</th>
        <th>speed</th>
        <th>Msg</th>
        <th>Date</th>
        <th></th>
        </thead>
        <tbody>
        @foreach( $allValues->reverse() as $readingsRow )
            <tr>
                <td>{{$readingsRow->id}}</td>
                <td>{{$readingsRow->meter_id}}</td>
                <td>{{$readingsRow->readings}}</td>
                <td>{{$readingsRow->diff}}</td>
                <td> <span class="text-danger">{{$readingsRow->perHour}} l/h</span></td>
                <td>{{$readingsRow->message}}</td>
                <td>{{$readingsRow->created_at}}</td>
                <td>
                    <button @click="showModal=true; setVal(item.id, item.name, item.age, item.profession)" class="btn btn-info">
                       <span class="glyphicon glyphicon-pencil"></span>
                    </button>

                    <button @click="disableReading({{$readingsRow->id}})" class="btn btn-danger danger">
                        <span class="fa fa-thumbs-down"></span>
                    </button>

                    <button class="btn btn-danger danger" @click="" >
                        <span class="fa fa-times "></span>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
@stop
@section('custom_scripts')
@stop
