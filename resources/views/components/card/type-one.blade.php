<div class="component-card-one swiper-slide">
    <div class="card-content">
        @if ($category)
            <a href="category/{{ $categorySlug }}" class="card-category">{{ $category }}</a>
        @endif
        <div class="card-image-container">
            @if ($image != null)
                {{-- <img onclick="location.href='#'" src="{{ asset("storage/$image") }}" alt="{{ $titleSlug }}">             --}}
                <img onclick="location.href='berita/{{ $postId }}-{{ $titleSlug }}'" src="{{ asset("storage/$image") }}" alt="{{ $titleSlug }}">            
            @else
                {{-- <img onclick="location.href='#'" src="{{ asset('img/no-image.png') }}" alt="no-image">             --}}
                <img onclick="location.href='berita/{{ $postId }}-{{ $titleSlug }}'" src="{{ asset('image/no-image.png') }}" alt="no-image">            
            @endif
        </div>
        <span class="card-date">{{ $date }}</span>
        {{-- <a href="#" class="card-title">{{ $title }}</a> --}}
        <a href="berita/{{ $postId }}-{{ $titleSlug }}" class="card-title">{{ $title }}</a>
    </div>
</div>