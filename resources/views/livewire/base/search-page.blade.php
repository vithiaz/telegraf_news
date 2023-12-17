@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/search-page.css') }}">
@endpush

<div class="search-page">
    <div class="container">
        <div class="page-path-section">
            <span class="base-path"><a href="/">TELEGRAF Today</a></span>
            <i class="fa-sharp fa-solid fa-angle-right"></i>
            <span class=""><a href="#">Pencarian</a></span>
            <i class="fa-sharp fa-solid fa-angle-right"></i>
            <span class="active">{{ $search_val }}</span>
        </div>
        <div class="content-section">
            <div class="content-head">
                <div class="secton-title">
                    <div class="sub-title">
                        Hasil Pencarian
                    </div>
                    <div class="title">
                        {{ $search_val }}
                    </div>
                </div>
                <div class="search-result-count">
                    <span>{{ $posts->count() }}</span>
                    <span>Hasil ditemukan</span>
                </div>
            </div>
            <div class="content-body">
                <div class="card-wrapper">
                    @forelse ($posts as $post)
                        <x-card.type-two
                            image="{{ $post->preview_image }}"
                            category="{{ $post->category ? $post->category->name : '' }}"
                            date='{{ $this->translate_date($post->created_at) }}'
                            title='{{ $post->title }}'
                            postId='{{ $post->id }}'
                        />
                    @empty
                        <div class="no-item">
                            <i class="fa-solid fa-circle-question"></i>
                            <span>Hasil pencarian tidak ditemukan!</span>
                        </div>
                    @endforelse
                </div>

                <div class="card-pagination">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>

    // Set news-page Padding Based on Navbar Height
    function adapt_page_to_navbar_height($height) {
        $('.search-page').css('padding-top', ($height + 10) + 'px');
    }
    adapt_page_to_navbar_height($('.navbar').height());

    $(window).resize(function () {
        adapt_page_to_navbar_height($('.navbar').height());
    });

    $(window).scroll(function () {
        adapt_page_to_navbar_height($('.navbar').height());
    });

    </script>
@endpush
