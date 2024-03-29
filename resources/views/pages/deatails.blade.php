@extends('layouts.main')
@include('helpers.functions')

@section('subtabs')
    <h5>Show Archived folders for {{\Illuminate\Support\Str::title($folderName)}}</h5>

@stop
@php $count=1; @endphp
@section('content')
    {{ $result->render() }}


    <div class="row">
        <div class="col-sm-6">
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
                @foreach($result as $folder)
                    <tr>
                        <td>{{$count++}}</td>
                        @if(is_array($folder))
                            @php

                                $href='/allfiles/details'.'?'.http_build_query(['q'=>'showfolderfiles', 'folder'=>$folderName, 'subfolder'=>class_basename($folder['origPath']) ]);
                            @endphp
                            <td><strong><a href="{{$href}}"> {{$folder['date']}}</a> </strong></td>
                            <td>{{$folder['size'] }}</td>
                            <td>{{ $folder['qty'] }}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $result->links() }}
@stop

