@extends('layouts.main')
@include('helpers.functions')

@section('subtabs')
    <h5>{{title_case($title)}}</h5>
    <div class="row">
        <div class="col-sm-2"><a href="javascript:history.back()" class="btn btn-default">Back</a></div>
        @if($next!='')
            <div class="col-lg-3"><a href="{{$next}}" class="btn btn-default">Next</a></div>
        @endif
    </div>


@stop
@php $count=1; @endphp
@section('content')
    {{--<img src="../../assets/pics/32177699_163468063_1498604760.jpg">--}}
    {{--<img src="../storage/ftp/32177699/today/32177699_163468063_1498604760.jpg" alt="a picture">--}}
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>FileName</th>
                    <th>Size</th>
                    <th>q-ty</th>
                    <th>IMG</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result as $key=>$val)
                    <tr>
                        <td>{{$count++}}</td>
                        @if(is_array($val))
                            @php
                                $href='/allfiles/details'.'?'.http_build_query(['q'=>'showfolderfiles', 'folder'=>$folder, 'subfolder'=>class_basename($val['origPath']), 'limit'=>500]);
                            @endphp
                            <td><strong><a href="{{$href}}"> {{$val['date']}}</a> </strong></td>
                            <td>{{getSize($val['origPath'])}}</td>
                            <td>{{getQty($val['origPath'])}}</td>
                        @else
                            <td>{{$val->getFileName()}}</td>
                            <td>{{getSize($val->getPathName())}}</td>
                            <td>1</td>
                            <td><img src="{{preg_replace('~[^\.]+storage~i', '/assets/pics', $val->getPathName())}}"
                                     style=" height: 20%"></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

