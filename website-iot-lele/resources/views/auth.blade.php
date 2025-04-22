<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Kolam</title>
    <link rel="shortcut icon" href="{{ asset('images/simko-icon.png') }}">

    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
    <style>

        @font-face {
            font-family: 'Play-Bold';
            src: url('/fonts/Play-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Montserrat-Regular';
            src: url('/fonts/Montserrat-Medium.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

    </style>
</head>

<body>
    @yield('auth-content')
</body>
</html>
