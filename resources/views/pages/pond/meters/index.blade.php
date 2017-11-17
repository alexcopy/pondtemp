@extends('layouts.main')

@section('content')

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMeter">
        Launch demo modal
    </button>

    <modal>
    <p slot="header">Test Title from Slot</p>
        Lorem ipsum
        <p slot="footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </p>

    </modal>
@stop

@section('custom_scripts')
    <script src="/assets/js/pond/meters.js"></script>
@stop
