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
                <tr>
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
                        <button class="btn btn-warning">Edit</button>
                        <button class="btn btn-danger">Del</button>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

@stop