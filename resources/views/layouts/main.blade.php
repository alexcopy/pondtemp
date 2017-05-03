<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy"
          content="default-src *; style-src 'self' https://* 'unsafe-inline'; script-src 'self' https://* 'unsafe-inline' 'unsafe-eval'"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/awesome.css">
  @yield('custom_css')
      <title>PondTemp</title>

</head>
<body>


<div class="container-fluid" role="main">
    @yield('tabs')
    <br/>
    <div class="panel">
        @yield('subtabs')
        <div class="panel-heading">
            <h4 class="panel-title">@yield('panelHeader') </h4>
        </div>
        <div class="panel-body">
            <br>
            @yield('content')
        </div>
    </div>
</div>
@section('footer_scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>

    @yield('custom_scripts')
@show

</body>
</html>