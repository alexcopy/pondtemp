@extends('layouts.main')
@include('helpers.functions')

@section('subtabs')
    <a href="javascript:history.back()" class="btn btn-default">Back</a>
    <h5>{{title_case($title)}}</h5>
@stop
@php $count=1; @endphp
@section('content')
    <div class="row">
        <div class="col-sm-4">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>FileName</th>
                    <th>Size</th>
                    <th>q-ty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key=>$val)
                    <tr>
                        <td>{{$count++}}</td>
                        @if(is_array($val))
                            @php
                                $href='/allfiles/details'.'?'.http_build_query(['q'=>'showfolderfiles', 'folder'=>$folder, 'subfolder'=>$val['folder'], 'limit'=>500]);
                            @endphp
                            <td><strong><a href="{{$href}}"> {{$val['date']}}</a> </strong></td>
                            <td>{{getSize($val['origPath'])}}</td>
                            <td>{{getQty($val['origPath'])}}</td>
                        @else
                            <td>{{$val->getFileName()}}</td>
                            <td>{{getSize($val->getPathName())}}</td>
                            <td>1</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop


