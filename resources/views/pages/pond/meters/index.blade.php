@extends('layouts.main')

@section('content')

    <stats></stats>

    <br>
    <br>

    <div class="row">
        <div class="col-sm-3 pull-right">
            <h5 class="text-danger">annual speed :<i id="minstr"> {{  $annualStats['hourly'] }}  </i> L/hour</h5>
            <h5 class="text-info">annual speed:<i id="minpnd"> {{  $annualStats['daily']  }}  </i> L/day</h5>
            <h5 class="text-info">annual used ({{round($annualStats['interval']/86400,0)}} days):<i id="minpnd">  {{$annualStats['used']}}</i> m3</h5>
        </div>

        <div class="col-sm-3 pull-right">
            <h5 class="text-danger">monthly speed :<i id="minstr"> {{  $monthStats['hourly'] }}  </i> L/hour</h5>
            <h5 class="text-info">monthly speed:<i id="minpnd"> {{  $monthStats['daily']  }}  </i> L/day</h5>
            <h5 class="text-info">monthly used :<i id="minpnd">  {{$monthStats['used']}}</i> m3</h5>
        </div>
        <div class="col-sm-3 pull-right">
            <h5 class="text-danger">weekly speed :<i id="minstr"> {{  $weekStats['hourly'] }}  </i> L/hour</h5>
            <h5 class="text-info">weekly speed:<i id="minpnd"> {{  $weekStats['daily']  }} </i> L/day</h5>
            <h5 class="text-info">weekly used :<i id="minpnd">  {{$weekStats['used']}}</i> m3</h5>
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
