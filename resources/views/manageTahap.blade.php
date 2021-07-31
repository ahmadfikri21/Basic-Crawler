@extends('templates.template')

@section('content')
    <h1 class="pageHeader">Manajemen Tahap Proyek</h1>
    <div class="mainpageBody">
        <div class="container">
            @if(Session::has('errNotice'))
                <div class="errorMessage">{{ Session::get('errNotice') }}</div>
            @elseif(Session::has('succNotice'))
                <div class="successMessage">{{ Session::get('succNotice') }}</div>
            @endif
            <div class="tableTitle">
                <h3>Tabel Manajemen Tahap Proyek</h3>
            </div>
            <div class="btnTambahAndSearch">
                <div>
                    <button id="tambahTrigger" class="button btn-lightBlue">+Tambah Tahap</button>
                </div>
                <form action="/Manage/ManageTahap" method="GET">
                    <div class="form-element">
                        <input type="text" name="keyword" placeholder="Keyword Pencarian...">
                        <input type="submit" class="button btn-lightBlue" value="Cari">
                    </div>
                </form>
            </div>
            <form action="/Manage/ManageTahap/hapusTahap">
                <table class="tableSitusLpse">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Tahap</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $page = (isset($_GET["page"])) ? $_GET["page"] : 1;
                            $i = ($page != 1) ? $page*$perPage-$perPage+1 : $page; 
                        ?>
                        @foreach($dataTahap as $data)
                        <?php
                            if (fmod($i,2) == 1) {
                                $class = "rowGanjil";
                            }else{
                                $class = "rowGenap";
                            }
                        ?>
                        <tr class="{{ $class }}">
                            <td><input type="checkbox" class="checkbox" name="checkbox[]" value="{{ $data->id_tahap }}"></td>
                            <td>{{ $i }}</td>
                            <td class="tahap">{{ $data->tahap }}</td>
                            <td> 
                                <button class="editTrigger"><img src="{{ asset('img/editButton.svg') }}"></button> &nbsp; 
                                <a href="/Manage/ManageTahap/hapusTahap/{{ $data->id_tahap }}" onclick="return confirm('Apakah anda yakin?')"><img src="{{ asset('img/trashRed.svg') }}"></a> 
                            </td>
                        </tr>
                        <?php $i++ ?>
                        @endforeach
                    </tbody>
                </table>
                <small><i>Menampilkan </i>{{ $dataTahap->count() }} <i>Data dari</i> {{ $dataTahap->total() }} <i>Data</i></small>
                <div class="tableFooter">
                    <div>
                        <input type="checkbox" id="checkAll">&nbsp;<label>Pilih Semua</label>
                        <input type="submit" value=" " id="deleteAll" onclick="return confirm('Apakah anda yakin?')">&nbsp;<label>Hapus</label>
                    </div>
                    {{ $dataTahap->links('templates.paginationLinks') }}
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal">    
        <div class="modalContent">
            <div class="modalHeader">
                <h2>Tambah Tahap Proyek</h2>
                <span class="closeModal">X</span>
            </div>
            <form id="formManageTahap" action="" method="POST">
                @csrf
                <div class="modalBody">
                    <div class="form-element">
                        <input type="hidden" name="idTahap" id="idTahap">
                        <label>Nama Situs</label>
                        <input type="text" name="tahap" id="tahap" placeholder="Tahap Proyek ..." value="{{ old('tahap') }}">
                        @if ($errors->has('tahap'))
                            <div class="formError">{{ $errors->first('tahap') }}</div>
                        @endif
                    </div>
                </div>
                <div class="modalFooter">
                    <button class="closeModal">Tutup</button>
                    <input type="submit" class="button btn-lightBlue" value="Tambah">
                </div>
            </form>
        </div>
    </div>

    <script>
        // fungsi untuk checkbox pilih semua
        $("#checkAll").change(function(e){
            if(this.checked){
                $(".checkbox").prop('checked',true);
            }else{
                $(".checkbox").prop('checked',false);
            }
        });

        // fungsi untuk trigger modal tambah
        $("#tambahTrigger").click(function(){
            $("#formManageTahap").attr("action","/Manage/ManageTahap/tambahTahap");
            
            $(".modalHeader h2").text("Tambah Tahap Proyek");
            $(".modalFooter input").val("Tambah");

            $(".modal").css("display","block");
        });

        // fungsi untuk trigger modal update
        $(".editTrigger").click(function(e){
            e.preventDefault();
            $("#formManageTahap").attr("action","/Manage/ManageTahap/editTahap");
            
            $(".modalHeader h2").text("Update Tahap Proyek");
            $(".modalFooter input").val("Update");

            // mengambil parent dari button edit( element tr )
            var tr = $(this).parent().parent();
            var idTahap = tr.children(":nth-child(1)").children().val();
            var tahap = tr.children(":nth-child(3)").text();

            // mengisi value textfield dengan data dari table
            $("#idTahap").val(idTahap);
            $("#tahap").val(tahap);

            $(".modal").css("display","block");
        });

        // fungsi untuk close modal
        $(".closeModal").click(function(e){
            e.preventDefault()
            $(".modal").css("display","none");
        });
    </script>
@endsection