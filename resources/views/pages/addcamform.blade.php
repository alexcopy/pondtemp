@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-5">
            <div class="col-lg-4">
                <h4 class="text-success text-center">Add New Cam</h4>
            </div>

            <br/>
            <br/>
            <br/>
            {!! Form::open(
                  [
                      'action' => 'CamsController@store',
                      'class' => 'form-horizontal',
                      'novalidate' => 'novalidate'
                      ]) !!}
            <div class="form-group">
                {!! Form::label('cam_id','ID',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('cam_id', null, ['placeholder'=>'0',"class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('name','name',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('name', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('login','login',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('login', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('password','pass',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('password', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('alarmServerUrl','Server URL',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('alarmServerUrl', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('port','port',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('port', null, ['placeholder'=>'8888', "class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('channel','channel',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::text('channel', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('is_cloudBased','is cloud',["class"=>"col-sm-2 control-label"]) !!}
                <div class="col-sm-3">
                    {!! Form::select('is_cloudBased',[ 'no','yes'] , ["class"=>"form-control"]) !!}
                </div>
            </div>

            <div class="form-group text-center">
                {!! Form::submit('Submit', null, ["class"=>"button-green form-control"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-sm-4">
            <h4 class="text-success">Cams in Db</h4>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>Cam Id</th>
                    <th>Cam Name</th>
                    <th>port</th>
                    <th>is_cloud</th>
                    <th>edit/delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cams as $cam)
                    <tr>
                        <td>{{$cam->cam_id}}</td>
                        <td>{{$cam->name}}</td>
                        <td>{{$cam->port}}</td>
                        <td>{{$cam->is_cloudBased?'yes':'no'}}</td>
                        <td>
                            <button id="edit_{{$cam->id}}" class="fa fa-pencil" aria-hidden="true"> edit</button>
                            <button id="del_{{$cam->id}}" class="button-red fa fa-times" aria-hidden="true"> del
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop