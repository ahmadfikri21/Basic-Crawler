<!-- kondisi untuk mengetahui apakah memerlukan pagination -->
@if($paginator->hasPages())
    <div class="pagination">
        <!-- Previous Page Link -->
        @if(!$paginator->onFirstPage())
            <a href="{{ $paginator->url(1) }}" class="page-item" rel="prev">&laquo;</a>
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item" rel="prev">&lsaquo;</a>
        @endif
        <!-- Pagination Elements -->
        <?php 
            // mengambil 7 value terakhir dari array pagination(7 dikarenakan pembagian pagination pada array berjumlah 7)
            $last7value = $paginator->lastPage() - 7+1;
            // kondisi jika halaman pagination dibawah 8, atau diatas 8 tetapi jumlah total halaman dibawah 15
            if($paginator->currentPage() < 8 || $paginator->currentPage() >= 8 && count($elements) == 1){
                // digunakan untuk memilih key array 1d dari variabel element yang berisi url pagination.
                $i = 0;
                // jika lebih kecil dari 3 maka iterasi akan dimulai dari key 1, jika lebih besar dari 3 maka iterasi dimulai dari page sekarang dikurang 3
                $current = ($paginator->currentPage() <= 3) ? 1 : $paginator->currentPage()-3;
                // menentukan key iterasi terakhir
                $last = ($current+7 >= count($elements[$i])) ? count($elements[$i])-$current+1 : 7;
                // kondisi pada for
                $kondisi = $current+$last;
            // kondisi jika halaman pagination diatas 8
            }elseif($paginator->currentPage() >= 8 && $paginator->currentPage() < $last7value){
                $i = 2;
                // current = 7
                $current = $paginator->currentPage()-3;
                // last = 13
                $last = $paginator->currentPage()+3;
                $kondisi = $last+1;
            }else{
                $i = 4;
                $current = $paginator->currentPage()-3;
                $last = ($paginator->currentPage()+3 <= $paginator->lastPage()) ? $paginator->currentPage()+3 : $paginator->lastPage() ;
                $kondisi = $last+1;
            }

            // looping untuk menampilkan pagination sesuai dengan page sekarang dan akhir (3 link disetiap sisi)
            for ($j=$current; $j < $kondisi; $j++):
        ?>
                @if($j == $paginator->currentPage())
                    <a class="page-item pg-active"><span>{{ $j }}</span></a></li>
                @else
                    <a href="{{ $elements[$i][$j] }}" class="page-item">{{ $j }}</a></li>
                @endif
        <?php 
            endfor;
        ?>
        <!-- End pagination elements -->

        <!-- Next Page Link -->
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-item"><span>&rsaquo;</span></a>
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-item"><span>&raquo;</span></a>
        @endif
    </div>

@endif