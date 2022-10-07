@extends('layouts.main')
@section('subtabs')
    <h5>{{\Illuminate\Support\Str::title($title)}}</h5>
@stop
@section('content')
    {{ $pictures->render() }}
    <br/>
    @foreach($pictures->chunk(6) as $pictureRow)
        <div class="row">
            <hr>
            @foreach($pictureRow as $picture)
                <div class="col-sm-2">
                    <a href="#" data-toggle="modal" data-target="#trainpopup" class="trainpopup">
                        <img src="{{$picture['imgpath']}}" class="img-fluid img-thumbnail"
                             name="{{$picture['origPath']}}"  loading="lazy"
                             date="{{$picture['date']}}"
                             style="border:1px solid #021a40;"/>
                    </a>
                    <div>
                        <b>{{date('d-m H:m', strtotime($picture['date']))  }}</b>
                    </div>
                </div>
            @endforeach
            <hr>
        </div>
    @endforeach

    <div class="modal fade" id="trainpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel"><span id="imgheader"></span></h6>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <img src="" id="imagepreview" style="width: 100%; height: 80%;">
                    <br><br>
                    <div class="row">
                        <div id="details" class="col-7"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    {{ $pictures->links() }}
@stop
@section('custom_scripts')
    <script>
        $(".trainpopup").on("click", function () {
            let date = $(this).children('img').attr('date');
            let name = $(this).children('img').attr('name');
            $('#details').html(' File Name: <b>'+ name+' </b>'+ '<br> Date and Time:  <b>'+ date +'</b>');
            $('#imagepreview').attr('src', $(this).children('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
        });
    </script>
@stop
