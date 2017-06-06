@extends('layouts.main')
@include('helpers.functions')
@section('subtabs')
    <h5>{{title_case($title)}}</h5>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-4">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>FileName</th>
                    <th>Size</th>
                </tr>
                </thead>
                <tbody>

                @foreach($result as $key=>$val)
                    <tr>
                        <td>{{$key}}</td>
                        @if(is_string($val))
                        @php
                            $href='/allfiles/details'.'?'.http_build_query(['q'=>'showfolderfiles', 'folder'=>$folder, 'subfolder'=>class_basename($val), 'limit'=>500]);
                        @endphp

                            <td><strong><a href="{{$href}}"> {{class_basename($val)}}</a> </strong></td>
                            <td>{{getSize($val)}}</td>
                        @else
                            <td>{{$val->getFileName()}}</td>
                            <td>{{getSize($val->getPathName())}}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop


