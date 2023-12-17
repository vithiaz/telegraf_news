@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/gallery-page.css') }}">
@endpush

<div>
    <div class="gallery-page">
        <div class="container">
            <div class="page-path-section">
                <span class="base-path"><a href="/">Telegraf</a></span>
                <i class="fa-sharp fa-solid fa-angle-right"></i>
                <span class="active">Gallery</span>
            </div>
            <div class="content-section">
                <div class="content-head">
                    <div class="section-title">
                        <span>
                            Gallery
                        </span>
                    </div>
                    <div class="gallery-search">
                        <input wire:model='gallery_search' type="text" placeholder="Cari di gallery">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
                @if ($search_active)
                    <div class="content-neck">
                        <span>Hasil Pencarian {{ $gallery_search }}</span>
                    </div>                
                @endif
                <div class="content-body">
                    <div id="gallery-image-list" class="card-wrapper">
    
                        @php
                            $num_id = 0;
                        @endphp
    
                        @forelse ($galleries as $gallery)
    
                            @if ($gallery->preview_image != null)
                                <div
                                    class="card"
                                    onclick="set_view_image({{ $num_id }})"
                                    data-image-url="{{ asset('storage/'.$gallery->preview_image) }}"
                                    data-title='{{ $gallery->title }}'
                                    data-title-slug='{{ $gallery->title_slug }}'
                                    data-date='{{ $this->translate_date($gallery->created_at) }}'
                                    data-post-id='{{ $gallery->id }}'
                                    >
                                    <img src="{{ asset('storage/'.$gallery->preview_image) }}" alt="{{ $gallery->title_slug }}">
                                    <div class="title-box">
                                        <span href="berita/{{ $gallery->id }}-{{ $gallery->title_slug }}">{{ $gallery->title }}</span>
                                    </div>
                                </div>
                            @endif
    
                            @php
                                $num_id++;
                            @endphp
    
                        @empty
                            <div class="no-item">
                                <i class="fa-solid fa-circle-question"></i>
                                <span>Tidak ada gallery untuk ditampilkan!</span>
                            </div>
                        @endforelse
    
                    </div>
    
                    <div class="button-wrapper">
                        @if ($more_post == false)
                            <button wire:click="load_more_post" class="post-more-button">Muat Lainnya</button>
                        @endif
                    </div>
    
                </div>
            </div>
        </div>
    </div>
    
    <div class="image-view-fullscreen">
        <div class="close-view">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="content">
            <div class="backdrop"></div>
            <div class="content-image">
                <div class="image-container">
                    <img id="view_image">
                </div>
                <div class="view-btn left-btn" onclick="prev_view();">
                    <i class="fa-sharp fa-solid fa-angle-left"></i>
                </div>
                <div class="view-btn right-btn" onclick="next_view();">
                    <i class="fa-sharp fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="desc">
                <div class="title">
                    <a id="view_title" href="#"></a>
                </div>
                <div class="sub-title">
                    <div id="view_date" class="date"></div>
                </div>
            </div>
        </div>
    </div>
</div>

    

@push('script')
<script>

    // Set news-page Padding Based on Navbar Height
    function adapt_page_to_navbar_height($height) {
        $('.gallery-page').css('padding-top', ($height + 10) + 'px');
    }
    adapt_page_to_navbar_height($('.navbar').height());

    $(window).resize(function () {
        adapt_page_to_navbar_height($('.navbar').height());
    });

    $(window).scroll(function () {
        adapt_page_to_navbar_height($('.navbar').height());
    });



    // Image Full Screen View Event
    let ImageViewId = 0;

    $('.close-view').click(function () {
        $('.image-view-fullscreen').removeClass('show');
    });
    
    $('.image-view-fullscreen .backdrop').click(function () {
        $('.image-view-fullscreen').removeClass('show');
    });

    let next_view = () => {
        if ( ImageViewId == ($('#gallery-image-list').children().length - 1) ) {
            set_view_image(0);
        }
        else {
            set_view_image(ImageViewId += 1);
        }
    }

    let prev_view = () => {
        if ( ImageViewId == 0 ) {
            set_view_image($('#gallery-image-list').children().length - 1);
        }
        else {
            set_view_image(ImageViewId -= 1);
        }
    }

    let set_view_image = ($order) => {
        ImageViewId = $order;
        
        let elemView = $('#gallery-image-list').children()[ImageViewId];
        full_screen_view(elemView);
    }

    let full_screen_view = ($elem) => {
        $('.image-view-fullscreen').addClass('show');

        $('#view_image').attr("src", $elem.dataset.imageUrl);
        $('#view_image').attr("alt", $elem.dataset.titleSlug);
        
        $('#view_title').text($elem.dataset.title);
        $('#view_title').attr("href", 'berita/'+$elem.dataset.postId+'-'+$elem.dataset.titleSlug);
        
        $('#view_date').text($elem.dataset.date);
    }

    // Key Pressed Event
    $( document ).keyup(function(e) {
        if ($('.image-view-fullscreen').hasClass('show')) {
            if (e.key == 'Escape') {
                $('.image-view-fullscreen').removeClass('show');
            }
            if (e.keyCode == 37) {
                prev_view();
            }
            if (e.keyCode == 39) {
                next_view();
            }
        }
    });

    </script>
@endpush