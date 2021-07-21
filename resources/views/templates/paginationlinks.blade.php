<!-- kondisi untuk mengetahui apakah memerlukan pagination -->
@if($paginator->hasPages())
    <div class="pagination">
        <!-- Previous Page Link -->
        @if(!$paginator->onFirstPage())
            <a href="{{ $paginator->url(1) }}" class="page-item" rel="prev">&laquo;</a>
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item" rel="prev">&lsaquo;</a>
        @endif

        <!-- Pagination Elements -->
        @foreach($elements as $element)
            <!-- Make three dots -->
            @if(is_string($element))
                <a  class="page-item"><span>{{$element}}</span></a></li>
            @endif

            <!-- Links Array Here -->
            @if(is_array($element))
                <?php 
                    // menentukan page sekarang
                    $current = ($paginator->currentPage() <= 3) ? 1 : $paginator->currentPage()-2;
                    // menentukan page akhir
                    $last = ($current+5 >= count($element)) ? count($element)-$current+1 : 5;

                    // looping untuk menampilkan pagination sesuai dengan page sekaran dan akhir (3 link disetiap sisi)
                    for ($i=$current; $i < $current+$last; $i++):
                ?>
                        @if($i == $paginator->currentPage())
                            <a class="page-item pg-active"><span>{{ $i }}</span></a></li>
                        @else
                            <a href="{{ $element[$i] }}" class="page-item">{{ $i }}</a></li>
                        @endif
                <?php 
                    endfor;
                ?>

            @endif

        @endforeach

        <!-- Next Page Link -->
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-item"><span>&rsaquo;</span></a>
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-item"><span>&raquo;</span></a>
        @endif
    </div>

@endif