@extends('templates.template')

@section('content')
    <h1 class="pageHeader">Manajemen Situs</h1>
    <div class="mainpageBody manageBody">
        <div class="container">
            <div class="choosePageBox">
                <h3 id="linkManajemenSitus">Manajemen <br> Situs Lpse</h3>
                <h3 id="linkManajemenTahap">Manajemen <br> Tahap Proyek</h3>
            </div>
        </div>    
    </div>

    <script>
        var linkSitus = $("#linkManajemenSitus");
        var linkTahap = $("#linkManajemenTahap");

        linkSitus.click(function(){
            window.location.href = "/Manage/ManageSite";
        });
        
        linkTahap.click(function(){
            window.location.href = "/Manage/ManageTahap";
        });
    </script>
@endsection