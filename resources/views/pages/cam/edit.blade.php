@extends('layouts.main')
@section('tabs')

    @if (Session::has('message')  )
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @if ( Html::ul($errors->all()) )
        <div class="alert alert-info">{{ Html::ul($errors->all()) }}</div>
    @endif
@stop
<h4 class="text-success text-center pull-left">Add New Cam</h4>
@section('content')

    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-4">
            {!! Form::model($cam, ['route' => ['cam.update', $cam->id], 'method' => 'PUT']) !!}

            <div class="form-group">
                {!! Form::label('cam_id','ID',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::text('cam_id', null, ['placeholder'=>'0',"class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('name','name',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::text('name', null, ["class"=>"form-control"]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('realpath','realpath',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::text('realpath', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('login','login',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::text('login', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('password','pass',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::password('password', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('alarmServerUrl','Server URL',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::url('alarmServerUrl', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('port','port',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::number('port', null, ['placeholder'=>'8888', "class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('channel','channel',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::number('channel', null, ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('is_cloudBased','is cloud',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::select('is_cloudBased',[ 'no','yes'] , ["class"=>"form-control"]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description','description',["class"=>" control-label"]) !!}
                <div class="">
                    {!! Form::textarea('description',null , ["class"=>"form-control"]) !!}
                </div>
            </div>
            <div class="form-group text-center">
                {{ Form::submit('Edit  '.$cam->name, array('class' => 'btn btn-success form-control col-sm-2 ')) }}
            </div>
            {{ Form::close() }}

        </div>

    </div>

@stop