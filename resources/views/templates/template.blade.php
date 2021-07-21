<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>LPSE Crawler</title>
</head>
<body>
    <div class="preloader">
        <div>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <h1><span>Please Wait</h1>
    </div>
    <nav>
        <div class="container">
            <a href="/Mainpage">
                <img src="{{ asset('img/Nav Logo.svg') }}">
            </a>
            <a class="logout" href="/Mainpage/logout">Logout</a>
        </div>
    </nav>

    @yield('content')

</body>
</html>