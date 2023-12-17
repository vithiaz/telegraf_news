<div class="component-card-two">
    <div class="card-content">
        <div class="card-image-container">

            @if ($image != null)
                <img onclick="location.href = '/berita/{{ $postId }}-{{ $title_slug }}'" src='{{ asset("storage/$image") }}' alt="{{ $title_slug }}">            
            @else
                <img onclick="location.href = '/berita/{{ $postId }}-{{ $title_slug }}'" src="{{ asset('image/no-image.png') }}" alt="no-image">            
            @endif

        </div>
        <div class="content-neck">
            @if ($category)
            <a href="/category/{{ $category_slug }}" class="card-category">{{ $category }}</a>        
            @endif
            <span class="card-date">{{ $date }}</span>
        </div>
        <span class="card-title"><a href="{{ route('news-detail', ["target" => $postId . '-' .$title_slug]) }}">{{ $title }}</a></span>


        {{-- <span class="card-title"><a href="/berita/{{ $postId }}-{{ $title_slug }}">{{ $title }}</a></span> --}}
    </div>
</div>