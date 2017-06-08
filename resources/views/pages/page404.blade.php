@extends('layouts.main')
@section('tabs')
    <h4>It's 404 Page</h4>
    <h5>{{$message}}</h5>
@stop
@section('subtabs')
    <a href="javascript:history.back()" class="btn btn-default">Back</a>
@stop
