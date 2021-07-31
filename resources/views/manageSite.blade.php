@extends('templates.template')

@section('content')
    <h1 class="pageHeader">Manajemen Situs</h1>
    <div class="mainpageBody">
        <div class="container">
            @if(Session::has('errNotice'))
                <div class="errorMessage">{{ Session::get('errNotice') }}</div>
            @elseif(Session::has('succNotice'))
                <div class="successMessage">{{ Session::get('succNotice') }}</div>
            @endif
            <div class="tableTitle">
                <h3>Tabel Manajemen Situs</h3>
                <select name="perPageSelect" id="perPageSelect">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="btnTambahAndSearch">
                <div>
                    <button id="tambahTrigger" class="button btn-lightBlue">+ Tambah Situs</button>
                </div>
                <form action="/Manage/ManageSite" method="GET">
                    <div class="form-element">
                        <input type="text" name="keyword" placeholder="Keyword Pencarian...">
                        <input type="submit" class="button btn-lightBlue" value="Cari">
                    </div>
                </form>
            </div>
            <form id="formCheckAll" action="">
                <table class="tableSitusLpse">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Nama Situs</th>
                            <th>Link</th>
                            <th>kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $page = (isset($_GET["page"])) ? $_GET["page"] : 1;
                            $i = ($page != 1) ? $page*$perPage-$perPage+1 : $page; 
                        ?>
                        @foreach($dataSitus as $data)
                        <?php
                            if (fmod($i,2) == 1) {
                                $class = "rowGanjil";
                            }else{
                                $class = "rowGenap";
                            }

                            if($data->status == "on"){
                                $src = asset('img/statusButtonRed.svg');
                            }else{
                                $src = asset('img/statusButton.svg');
                                $class = "rowOff";
                            }
                        ?>
                        <tr class="{{ $class }}">
                            <td><input type="checkbox" class="checkbox" name="checkbox[]" value="{{ $data->id_situs }}"></td>
                            <td>{{ $i }}</td>
                            <td class="namaSitus">{{ $data->nama_situs }}</td>
                            <td class="link">{{ $data->link }}</td>
                            <td class="kategori">{{ $data->kategori }}</td>
                            <td class="status">{{ $data->status }}</td>
                            <td> 
                                <button class="editTrigger"><img src="{{ asset('img/editButton.svg') }}"></button> &nbsp; 
                                <a href="/Manage/ManageSite/changeStatus/{{ $data->id_situs }}"><img src="{{ $src }}" alt=""></a> &nbsp; 
                                <a href="/Manage/ManageSite/hapusSitus/{{ $data->id_situs }}"><img src="{{ asset('img/trashRed.svg') }}" onclick="return confirm('Apakah anda ingin menghapus situs ini?')"></a> 
                            </td>
                        </tr>
                        <?php $i++ ?>
                        @endforeach
                    </tbody>
                </table>
                <small><i>Menampilkan </i>{{ $dataSitus->count() }} <i>Data dari</i> {{ $dataSitus->total() }} <i>Data</i></small>
                <div class="tableFooter">
                    <div>
                        <input type="checkbox" id="checkAll">&nbsp;<label>Pilih Semua</label>
                        <input type="submit" value=" " id="changeSelected">&nbsp;<label>Status on/off</label>
                        <input type="submit" value=" " id="deleteSelected">&nbsp;<label>Hapus</label>
                    </div>
                    {{ $dataSitus->links('templates.paginationLinks') }}
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal">    
        <div class="modalContent">
            <div class="modalHeader">
                <h2>Tambah Situs Lpse</h2>
                <span class="closeModal">X</span>
            </div>
            <form id="formManageSitus" action="" method="POST">
                @csrf
                <div class="modalBody">
                    <div class="form-element">
                        <input type="hidden" name="idSitus" id="idSitus">
                        <label>Nama Situs</label>
                        <input type="text" name="namaSitus" id="namaSitus" placeholder="Nama Situs..." value="{{ old('namaSitus') }}">
                        @if ($errors->has('namaSitus'))
                            <div class="formError">{{ $errors->first('namaSitus') }}</div>
                        @endif
                    </div>
                    <div class="form-element">
                        <label>Link</label>
                        <input type="text" name="link" id="link" placeholder="Link Situs..." value="{{ old('link') }}">
                        @if ($errors->has('link'))
                            <div class="formError">{{ $errors->first('link') }}</div>
                        @endif
                    </div>
                    <div class="form-element">
                        <label>Kategori</label>
                        <select name="kategori" id="kategori">
                            <option value="">Pilih Kategori</option>
                            <option value="Kementerian">Kementerian</option>
                            <option value="Instansi">Instansi</option>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Kabupaten">Kabupaten</option>
                            <option value="Kota">Kota</option>
                        </select>
                        @if ($errors->has('kategori'))
                            <div class="formError">{{ $errors->first('kategori') }}</div>
                        @endif
                    </div>
                    <div class="form-element">
                        <label>Status</label><br>
                        <input type="radio" name="status" id="statusOn" value="On" checked>&nbsp; On &nbsp;
                        <input type="radio" name="status" id="statusOff" value="Off">&nbsp; Off
                        @if ($errors->has('status'))
                            <div class="formError">{{ $errors->first('status') }}</div>
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
        var currentPage = "<?= $dataSitus->currentPage() ?>"
        // fungsi untuk select perPage
        $("#perPageSelect").change(function(e){
            $.ajax({
                url: "/Manage/ManageSite/changePerPageManage",
                method: 'get',
                datatype: 'json',
                data : { value : $("#perPageSelect :selected").text() },
                success: function(result){
                    if(currentPage > 1){
                        window.location.href = "/Manage/ManageSite";
                    }else{
                        location.reload();
                    }
                }
            });
        });

        $("#perPageSelect").val(<?= $perPage ?>)

        // fungsi untuk checkbox pilih semua
        $("#checkAll").change(function(e){
            if(this.checked){
                $(".checkbox").prop('checked',true);
            }else{
                $(".checkbox").prop('checked',false);
            }
        });

        // untuk mengganti action dari form delete pilihan
        $("#changeSelected").one("click",function(e){
            e.preventDefault();

            $("#formCheckAll").attr("action","/Manage/ManageSite/changeStatus");

            $("#formCheckAll").submit();
        });

        // untuk mengganti action dari form delete pilihan
        $("#deleteSelected").on("click",function(e){
            e.preventDefault();

            if(confirm("Apakah anda ingin menghapus pilihan ?")){
                $("#formCheckAll").attr("action","/Manage/ManageSite/hapusSitus");
                $("#formCheckAll").submit();
            }
        });

        // fungsi untuk trigger modal tambah
        $("#tambahTrigger").click(function(){
            $("#formManageSitus").attr("action","/Manage/ManageSite/tambahSitus");
            
            $(".modalHeader h2").text("Tambah Situs Lpse");
            $(".modalFooter input").val("Tambah");

            $(".modal").css("display","block");
        });

        // fungsi untuk trigger modal update
        $(".editTrigger").click(function(e){
            e.preventDefault();
            $("#formManageSitus").attr("action","/Manage/ManageSite/editSitus");
            
            $(".modalHeader h2").text("Update Situs Lpse");
            $(".modalFooter input").val("Update");

            // mengambil parent dari button edit( element tr )
            var tr = $(this).parent().parent();
            var idSitus = tr.children(":nth-child(1)").children().val();
            var namaSitus = tr.children(":nth-child(3)").text();
            var link = tr.children(":nth-child(4)").text();
            var kategori = tr.children(":nth-child(5)").text();
            var status = tr.children(":nth-child(6)").text();

            // mengisi value textfield dengan data dari table
            $("#idSitus").val(idSitus);
            $("#namaSitus").val(namaSitus);
            $("#link").val(link);
            $("#kategori").val(kategori);
            $("#status").val(status);
            if(status == "off"){
                $("#statusOn").prop("checked",false)
                $("#statusOff").prop("checked",true)
            }else if(status == "on"){
                $("#statusOn").prop("checked",true)
                $("#statusOff").prop("checked",false)
            }

            $(".modal").css("display","block");
        });

        // fungsi untuk close modal
        $(".closeModal").click(function(e){
            e.preventDefault()
            $(".modal").css("display","none");
        });
    </script>
@endsection