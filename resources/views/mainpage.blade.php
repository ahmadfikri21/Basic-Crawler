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
                            <option value="{{ $select->nama_situs }}">&nbsp;{{ $select->nama_situs }}</option>
                        @endforeach
                    </select>
                    <select name="jenisPengadaan" id="jenisPengadaan">
                        <option value="">Jenis Pengadaan</option>
                        @foreach($jenis_pengadaan as $jenis )
                            <option value="{{ $jenis->nama_jenis }}">&nbsp;{{ $jenis->nama_jenis }}</option>
                        @endforeach
                    </select>
                    <select name="tahapProyek" id="tahapProyek">
                        <option value="">Tahap Proyek</option>
                        @foreach($tahap_proyek as $tahap )
                            <option value="{{ $tahap->tahap }}">&nbsp;{{ $tahap->tahap }}</option>
                        @endforeach
                    </select>
                    <input type="submit" class="button btn-darkblue" value="Cari">
                </div>
            </form>
            <select name="perPageSelect" id="perPageSelect" style="width:72px; margin-bottom:10px; margin-top: -10px;">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select><br>
            <!-- List Hasil Pencarian -->
            <?php
                // untuk menghitung tulisan "1-100"
                $startCount = $lpse->currentPage()*$perPage-$perPage+1;
                $lastCount = $startCount+count($lpse->items())-1;
            ?>
            <small class="dataCount"><i>Menampilkan </i> {{ $startCount }} - {{ $lastCount }}<i> Data Dari </i>{{ $lpse->total() }} <i> Data</i></small>
            <div class="listLpse">
            <?php 
                if(count($lpse)):
                    $page = (isset($_GET["page"])) ? $_GET["page"] : 1;
                    $i = ($page != 1) ? $page*$perPage-$perPage+1 : $page;
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
                            <a href="<?= $data[5]."/lelang/".$data[0] ?>" class="judulPengadaan" target="_blank"><?= $data[1] ?></a>
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
        var currentPage = "<?= $lpse->currentPage() ?>"
        $("#perPageSelect").change(function(e){
            $.ajax({
                url: "/Mainpage/changePerPageMain",
                method: 'get',
                datatype: 'json',
                data : { 
                    value : $("#perPageSelect :selected").text()
                },
                success: function(result){
                    if(currentPage > 1){
                        window.location.href = "/Mainpage";
                    }else{
                        location.reload();
                    }
                }
            });
        });

        $("#perPageSelect").val(<?= $perPage ?>)
    </script>
@endsection