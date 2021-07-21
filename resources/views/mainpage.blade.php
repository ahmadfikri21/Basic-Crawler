@extends('templates.template')

@section('content')
    <h1 class="pageHeader">Tender</h1>
    <div class="mainpageBody">
        <div class="container">
            <!-- search input pada page -->
            <form action="/Mainpage" method="GET">
                <div class="form-element searchBox">
                    <input type="text" name="keyword" value="<?= (isset($_GET["keyword"])) ? $_GET["keyword"] : "" ; ?>" placeholder="Keyword Pencarian...">
                    <select name="lpseSite" id="lpseSite">
                        <option value="">Semua LPSE</option>
                        @foreach($urlLpse as $select )
                            <option value="{{ $select }}">{{ $select }}</option>
                        @endforeach
                    </select>
                    <select name="filterHps" id="filterHps">
                        <option value="">HPS</option>
                        <option value="1">< 500 jt</option>
                        <option value="2">500 jt - 1 M</option>
                        <option value="3">> 1 M</option>
                    </select>
                    <input type="submit" class="button btn-darkblue" value="Cari">
                </div>
            </form>
            <!-- List Hasil Pencarian -->
            <small class="dataCount"><i>Menampilkan </i>{{ $lpse->count() }}<i> Data Dari </i>{{ $lpse->total() }} <i> Data</i></small>
            <div class="listLpse">
            <?php 
                if(count($lpse)):
                    $page = (isset($_GET["page"])) ? $_GET["page"] : 1;
                    $i = ($page != 1) ? $page*10-10+1 : $page;
                    $nominal = "jt";
                    foreach($lpse as $data):
                        if($data[2]<100){
                            $nominal = "M";
                        }
            ?>
                        <div class="hasilLpse"> 
                        <span class="nomorPengadaan"><?= $i ?></span>
                        <div class="judulPengadaan">
                            <small class="dataCount" style="font-weight:normal;">{{ $data[8] }} - {{ $data[6] }} - {{ $data[7] }}</small><br>
                            <a href="<?= $data[5]."eproc4/lelang/".$data[0] ?>" class="judulPengadaan" target="_blank"><?= $data[1] ?></a>
                            <br>
                            <span><?= $data[4] ?></span>
                        </div>
                        <span class="button btn-green nilaiHps">Rp. <?= $data[2]." ".$nominal ?></span>
                        <div class="deadlineAkhir">
                            <span><?= $data[3] ?></span>
                        </div>
                        </div>
            <?php
                        $i++; 
                    endforeach; 
                else:
            ?>
                <div class='emptyNotice'>
                    <img src="{{ asset('img/not found.svg') }}">
                    <h1>Data tidak ditemukan</h1>
                </div>
            <?php
                endif;
            ?>
            </div>
            <!-- Pagination Links -->
            {{ $lpse->links('templates.paginationlinks') }}
        </div>
    </div>
    <script>
        var loader = $(".preloader");
        var lpseSite = "<?php echo (isset($_GET["lpseSite"])) ? $_GET["lpseSite"] : "none" ; ?>"    
        var filterHps = "<?php echo (isset($_GET["filterHps"])) ? $_GET["filterHps"] : "" ; ?>"    

        // fungsi untuk reset select ketika input tidak ada di url
        function clearSelect(select,condition,input){
            if (input != condition) {
                select.val(input)
            }else{
                select.val("")
            }
        }

        clearSelect($("#lpseSite"),"none",lpseSite)
        clearSelect($("#filterHps"),"",filterHps)

        window.addEventListener("load",function(){
            loader.css("display","none")
        })
        // window.addEventListener("load",function(){
        // });
    </script>
@endsection