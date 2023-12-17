<div class="{{ $this->class }}">
    <div class="slot-title">
        <span>Update</span>
    </div>
    <div class='slot-card-container @if($posts == null) grow @endif'>
        @forelse ($posts as $post)
        <div class="card-type-two">
                <div class="card-image-container">

                    @if ($post->preview_image != null)
                        {{-- marker! --}}
                        {{-- <img onclick="location.href='#'" src='{{ asset("storage/$post->preview_image") }}' alt="{{ $post->title_slug }}_image"> --}}
                        <img onclick="location.href='berita/{{ $post->id }}-{{ $post->title_slug }}'" src='{{ asset("storage/$post->preview_image") }}' alt="REPLACE_THIS">
                    @else
                        {{-- marker! --}}
                        {{-- <img onclick="location.href='#'" src="{{ asset('img/no-image.png') }}" alt="{{ $post->title_slug }}_image"> --}}
                        <img onclick="location.href='berita/{{ $post->id }}-{{ $post->title_slug }}'" src="{{ asset('image/no-image.png') }}" alt="REPLACE_THIS">
                    @endif

                </div>
                <div class="card-content">
                    @if ($post->category)
                        {{-- marker! --}}
                        {{-- <a href="#" class="card-category"> --}}
                        <a href="category/{{ $post->category->name_slug }}" class="card-category">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    <div class="card-date">
                        {{ translate_date($post->created_at) }}
                    </div>
                    <a class="card-title" href="berita/{{ $post->id }}-{{ $post->title_slug }}">
                        {{ $post->title }}
                    </a>
                    <div class="card-details">
                        {{ get_first_paragraph($post->body) }}
                    </div>
                </div>
            </div>
        @empty    
            <div class="no-post">
                <span>Belum ada postingan pekan ini. . .</span>
            </div>
        @endforelse
    </div>
    <div class="button-wrapper">
        @if ($posts_next_count > 0)
            <button wire:click="load_more_post" class="post-more-button">Muat Lainnya</button>
        @else
            {{-- marker! --}}
            {{-- <a href="#" class="post-redirect-button">Lihat Selengkapnya</a> --}}
            <a href="/berita" class="post-redirect-button">Lihat Selengkapnya</a>
        @endif
    </div>
</div>