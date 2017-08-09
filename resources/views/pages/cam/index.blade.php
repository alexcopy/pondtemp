@extends('layouts.main')
@section('custom_css')
    <style>
        .panel {
            border: 0;
            box-shadow: none;
        }
    </style>
@stop

@section('tabs')
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
@stop

@section('subtabs')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-lg-5 pull-right">
                    <a class="btn btn-success" href="{{ URL::to('cam/create') }}">
                        <i class="fa fa-camera" aria-hidden="true"></i> Add new
                    </a>
                </div>

                <div class="col-lg-8">
                    <h4 class="text-success">Cams in Db</h4>
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Cam Id</th>
                            <th>Cam Name</th>
                            <th>url</th>
                            <th>port</th>
                            <th>is_cloud</th>
                            <th>edit/delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach($cams as $cam)
                            <tr id="camrow_{{$cam->id}}">
                                <td>{{$i++}}</td>
                                <td>{{$cam->cam_id}}</td>
                                <td>{{$cam->name}}</td>
                                <td><a href="#">link</a></td>
                                <td>{{$cam->port}}</td>
                                <td>{{$cam->is_cloudBased?'yes':'no'}}</td>
                                <td>
                                    <div class="row">
                                        <a class="btn btn-xs btn-success"
                                           href="{{URL::to('cam/'.$cam->id)}}"><span
                                                    class="fa fa-address-card" aria-hidden="true"></span> Show
                                        </a>

                                        <a class="btn btn-xs btn-warning"
                                                href="{{URL::to('cam/'.$cam->id.'/edit')}}"><span
                                                class="fa fa-pencil" aria-hidden="true"></span> Edit
                                        </a>
                                        <button class="btn btn-xs btn-danger"
                                                onclick="ALEX.camdelete({{$cam->id}})"><span
                                                    class="fa fa-times" aria-hidden="true"></span> DEL
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@stop
@section('custom_scripts')
    <script type="text/javascript" src="../assets/js/custom/altercams.js"></script>
@stop