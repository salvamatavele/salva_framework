<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900" rel="stylesheet">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/error.css') }}" />
	<title>{{ $title }}</title>

</head>
<body>
<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>Ooops!</h1>
			</div>
            @if ($errocode)
               @if ($errocode == 400)
               <h2>{{ $errocode }} -Bad Request</h2> 
               @endif 
               @if ($errocode == 404)
               <h2>{{ $errocode }} -Not Found</h2> 
               @endif
               @if ($errocode == 405)
               <h2>{{ $errocode }} -Method Not Allowed and</h2> 
               @endif
               @if ($errocode == 501)
               <h2>{{ $errocode }} -Not Implemented</h2> 
               @endif
            @else
            <h2>Some error when load the page</h2> 
            @endif
			<p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
			<a href="{{ $router->route('home') }}">Go To Homepage</a>
		</div>
	</div>
</body>
</html>