<div class="components most-view-container">
    <div class="title">
        Pembaca Terbanyak
    </div>
    <div class="card-view-wrapper">
        @forelse ($most_view_posts as $i=>$post)
            <div class="card-view">
                <div class="number">
                    # {{ $i+1 }}
                </div>
                <div class="card-title">
                    {{-- <a href="#">{{ $post->title }}</a> --}}
                    <a href="/berita/{{ $post->id }}-{{ $post->title_slug }}">{{ $post->title }}</a>
                </div>
            </div>
        @empty
            <div class="card-view">
                <div class="card-title no-post">
                    Belum Ada Postingan
                </div>
            </div>
        @endforelse
    </div>
    <button class="most-view-button">
        {{-- <span onclick="location.href ='#'">Selengkapnya</span> --}}
        <span onclick="location.href ='/berita'">Selengkapnya</span>
        <i class="fa-solid fa-angle-right"></i>
    </button>
</div>