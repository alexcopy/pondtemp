@extends('layouts.main')

@section('tabs')
    @if (Session::has('message')  )
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @if ( Html::ul($errors->all()) )
        <div class="alert alert-info">{{ Html::ul($errors->all()) }}</div>
    @endif
@stop

@section('content')
<!-- will be used to show any messages -->

<div class="row">
    <div class="col-lg-8">
        <div class="col-lg-3">
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
            <div class="col-sm-5">
                {!! Form::text('cam_id', null, ['placeholder'=>'0',"class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','name',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::text('name', null, ["class"=>"form-control"]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('realpath','realpath',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::text('realpath', null, ["class"=>"form-control"]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('login','login',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::text('login', null, ["class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('password','pass',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::password('password', null, ["class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('alarmServerUrl','Server URL',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::url('alarmServerUrl', null, ["class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('port','port',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::number('port', null, ['placeholder'=>'8888', "class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('channel','channel',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::number('channel', null, ["class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('is_cloudBased','is cloud',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::select('is_cloudBased',[ 'no','yes'] , ["class"=>"form-control"]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description','description',["class"=>"col-sm-2 control-label"]) !!}
            <div class="col-sm-5">
                {!! Form::textarea('description',null , ["class"=>"form-control"]) !!}
            </div>
        </div>

        <div class="form-group text-center">
            {!! Form::submit('Submit', null, ["class"=>"btn btn-success form-control"]) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>



@stop