<?php
    // untuk menentukan link yang active
    $manage = "";
    $home = "";

    if(str_contains($title,"Mana")){
        $manage = "nav-active";
    }else if(str_contains($title,"Home")){
        $home = "nav-active";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Basic Crawler : {{ $title }}</title>
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
            <a class="navLinks" href="/Mainpage/logout">Logout</a>
            <a class="navLinks {{ $manage }}" href="/Manage">Manage</a>
            <a class="navLinks {{ $home }}" href="/Mainpage">Home</a>
        </div>
    </nav>

    @yield('content')
    <script>
        var loader = $(".preloader");
        var lpseSite = "<?php echo (isset($_GET["lpseSite"])) ? $_GET["lpseSite"] : "none" ; ?>"    
        var jenisPengadaan = "<?php echo (isset($_GET["jenisPengadaan"])) ? $_GET["jenisPengadaan"] : "" ; ?>"    
        var tahapProyek = "<?php echo (isset($_GET["tahapProyek"])) ? $_GET["tahapProyek"] : "" ; ?>"    

        // fungsi untuk reset select ketika input tidak ada di url
        function clearSelect(select,condition,input){
            if (input != condition) {
                select.val(input)
            }else{
                select.val("")
            }
        }

        clearSelect($("#lpseSite"),"none",lpseSite)
        clearSelect($("#jenisPengadaan"),"",jenisPengadaan)
        clearSelect($("#tahapProyek"),"",tahapProyek)

        window.addEventListener("load",function(){
            loader.css("display","none")
        })
        // window.addEventListener("load",function(){
        // });
    </script>
</body>
</html>