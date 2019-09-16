@extends('layouts.main')

@section('content')

    <stats></stats>

    <br>


    <div class="row">
        <div class="col-sm-3 pull-left">
            <h5 class="text-danger">weekly speed :<i> {{  $weekStats['hourly'] }}  </i> L/hour</h5>
            <h5 class="text-info">weekly speed:<i> {{  $weekStats['daily']  }} </i> L/day</h5>
            <h5 class="text-info">weekly used :<i>  {{$weekStats['used']}}</i> m3</h5>
        </div>

        <div class="col-sm-3 pull-left">
            <h5 class="text-danger">monthly speed :<i> {{  $monthStats['hourly'] }}  </i> L/hour</h5>
            <h5 class="text-info">monthly speed:<i> {{  $monthStats['daily']  }}  </i> L/day</h5>
            <h5 class="text-info">monthly used :<i>  {{$monthStats['used']}}</i> m3</h5>
        </div>
        <div class="col-sm-3 pull-left">
            <h5 class="text-danger">annual speed :<i> {{  $annualStats['hourly'] }}  </i> L/hour</h5>
            <h5 class="text-info">annual speed:<i> {{  $annualStats['daily']  }}  </i> L/day</h5>
            <h5 class="text-info">annual used ({{round($annualStats['interval']/86400,0)}}
                days):<i>  {{$annualStats['used']}}</i> m3</h5>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>meter</th>
        <th>read</th>
        <th>used</th>
        <th>speed</th>
        <th>MSG</th>
        <th>Date</th>
        <th></th>
        </thead>
        <tbody>
        @foreach( $allValues->reverse() as $readingsRow )
            <tr>
                <td>{{$readingsRow->id}}</td>
                <td><span class="limtext">{{$readingsRow->meterName}}</span></td>
                <td>{{$readingsRow->readings}}</td>
                <td>{{$readingsRow->diff}}</td>
                <td><span class="text-danger">{{$readingsRow->perHour}} l/h</span></td>
                <td><span class="limtext_100">{{$readingsRow->message}}</span></td>
                <td>{{$readingsRow->created_at->format('d/m   H:m')}}</td>
                <td>
                    <button @click="showModal=true; setVal(item.id, item.name, item.age, item.profession)"
                            class="btn btn-xs btn-info">
                        <i class="glyphicon glyphicon-pencil"></i>
                    </button>

                    <button @click="disableReading({{$readingsRow->id}})" class="btn btn-xs btn-danger danger">
                        <i class="fa fa-thumbs-down"></i>
                    </button>

                    <button class="btn btn-danger btn-xs danger" @click="">
                        <i class="fa fa-times "></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('custom_scripts')
@stop
