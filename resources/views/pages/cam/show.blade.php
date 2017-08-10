@extends('layouts.main')
@section('tabs')

@stop
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Cam ID</th>
                    <th>Name</th>
                    <th>Login</th>
                    <th>Password</th>
                    <th>Alarm Server Url</th>
                    <th>Port</th>
                    <th>Channel</th>
                    <th>Client Exists Url</th>
                    <th>is_cloudBased</th>
                    <th>created_at</th>
                    <th>updated_at</th>
                    <th>actions</th>
                </tr>
                </thead>
                <tbody>
                <tr id="camrow_{{$cam->id}}">
                    <td>{{ $cam->id }}</td>
                    <td>{{ $cam->cam_id }}</td>
                    <td>{{ $cam->name }}</td>
                    <td>{{ $cam->login }}</td>
                    <td> password</td>
                    <td>{{ $cam->alarmServerUrl }}</td>
                    <td>{{ $cam->port }}</td>
                    <td>{{ $cam->channel }}</td>
                    <td>{{ $cam->clientExistsUrl }}</td>
                    <td>{{ $cam->is_cloudBased }}</td>
                    <td>{{ $cam->created_at }}</td>
                    <td>{{ $cam->updated_at }}</td>
                    <td>
                        <a class="btn btn-xs btn-warning"
                           href="{{URL::to('cam/'.$cam->id.'/edit')}}"><span
                                    class="fa fa-pencil" aria-hidden="true"></span> Edit
                        </a>


                        <button class="btn btn-xs btn-danger"
                                onclick='ALEX.camdelete(" {{Request::url()}} ")'>
                            <span class="fa fa-times" aria-hidden="true"></span> DEL
                        </button>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

@stop
@section('custom_scripts')
    <script type="text/javascript" src="../assets/js/custom/altercams.js"></script>
@stop