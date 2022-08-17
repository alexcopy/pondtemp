@extends('layouts.main')
@include('helpers.functions')

@section('subtabs')
    <h5>Show Archived folders for {{title_case($folderName)}}</h5>

@stop
@php $count=1; @endphp
@section('content')
    {{ $result->links() }}

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
                            <td>{{getSize($folder['origPath'])}}</td>
                            <td>{{getQty($folder['origPath'])}}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $result->links() }}
@stop

