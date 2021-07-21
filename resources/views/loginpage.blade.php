<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Lpse Crawler : Login</title>
</head>
<body class="loginBackground">
    <img src="{{ asset('img/Nav Logo.svg') }}">
    <div class="loginBox">
        <form action="/loginProcess" method="POST">
            @csrf
            <div class="form-element">
                <label>Username</label><br>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username...">
                @if ($errors->has('username'))
                    <div class="formError">{{ $errors->first('username') }}</div>
                @endif
            </div>
            <div class="form-element">
                <label>Password</label><br>
                <input type="password" name="password" placeholder="Password...">
                @if ($errors->has('password'))
                    <div class="formError">{{ $errors->first('password') }}</div>
                @endif
            </div>
            @if(Session::has('notice'))
                <div class="formError">{{ Session::get('notice') }}</div>
            @endif
            <?php 
                // if($this->session->userdata('notice')){
                    // echo "<div class='formError'>".$this->session->userdata('notice')."</div>";
                    // $this->session->unset_userdata("notice");
                // } 
            ?>
            <input type="submit" value="Login" class="button btn-darkblue">
        </form>
    </div>
</body>
</html>