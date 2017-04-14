<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "//www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy"
          content="default-src *; style-src 'self' https://* 'unsafe-inline'; script-src 'self' https://* 'unsafe-inline' 'unsafe-eval'"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Proj</title>

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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    @yield('custom_scripts')
@show

</body>
</html>