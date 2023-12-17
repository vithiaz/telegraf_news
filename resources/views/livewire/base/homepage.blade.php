@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
@endpush

<div class="homepage">

    <div class="header-section" style="background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.4), rgba(0,0,0,0)), url({{ asset('img/landing.jpg')}}) no-repeat center center / cover;">
    {{-- @if ($page_wallpaper)
        <div class="header-section" style="background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.4), rgba(0,0,0,0)), url({{ asset('storage/'.$page_wallpaper)}}) no-repeat center center / cover;">
    @else
        <div class="header-section" style="background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.4), rgba(0,0,0,0)), no-repeat center center / cover;">
    @endif --}}

        <div class="container">
            <div class="header-logo-container">
                <div class="image-container">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name_slug', 'news') }}_logo">
                </div>
                <span class="brand">Telegraf</span>
            </div>
            <div id="home-content-nav" class="header-nav">
                <span class="active sport-nav"><a  href="{{ route('homepage') }}">Today</a></span>
                {{-- <span class="env-nav"><a  href="{{ route('env-home') }}">Environment</a></span>
                <span class="history-nav"><a  href="{{ route('history-home') }}">History</a></span> --}}
            </div>
            <div class="header-content">
                <div class="main-content">
                    <div class="main-image-container">
                        @if ($newest_post_hero)

                            @if ($newest_post_hero->preview_image != null)
                                <img src="{{ asset('storage/'.$newest_post_hero->preview_image) }}" alt="{{ $newest_post_hero->title_slug.'_headline' }}">                                                        
                            @else
                                <img src="{{ asset('img/no-image.png') }}" alt="{{ 'no-image' }}">
                            @endif

                        @else
                            <div class="no-image">
                                <i class="fa-solid fa-0"></i>
                                <i class="fa-solid fa-0"></i>
                                <i class="fa-solid fa-0"></i>
                            </div>
                        @endif
                    </div>
                    <div class="details-container">
                        <span class="header-title">Terbaru</span>

                        @if ($newest_post_hero)
                            <a class="details-title" href="berita/{{ $newest_post_hero->id }}-{{ $newest_post_hero->title_slug }}">{{ $newest_post_hero->title }}</a>
                            <span class="details-date">{{ translate_date($newest_post_hero->created_at) }}</span>
                            <p class="details-text">{!! $this->get_first_paragraph($newest_post_hero->body) !!}</p>                            
                        @else
                            <div class="no-post">
                                <span>belum ada postingan</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="suggestion">
                    @forelse ($newest_posts as $post)
                        <div class="suggestion-items">
                            {{-- <a class="title" href="#">{{ $post->title }}</a> --}}
                            <a class="title" href="berita/{{ $post->id }}-{{ $post->title_slug }}">{{ $post->title }}</a>
                            <span class="date">{{ translate_date($post->created_at) }}</span>
                        </div>
                    @empty
                        <div class="no-posts">
                            <span>belum ada postingan</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="content-section">
        <div class="container">
            <div class="slot-1">
                <div class="slot-title">
                    <span>Populer</span>
                    <div class="wrapper-btn-container">
                        <div class="swipe-btn swipe-prev-btn"><i class="fa-solid fa-angle-left"></i></div>
                        <div class="swipe-btn swipe-next-btn"><i class="fa-solid fa-angle-right"></i></div>    
                    </div>
                </div>
                <div class="slot-card-container swiper swiper-1">
                    <div class="swiper-wrapper">
                        @foreach ($weeks_popular_posts as $post)
                            <x-card.type-one
                                image="{{ $post->preview_image }}"
                                category="{{ $post->category ? $post->category->name : '' }}"
                                categorySlug="{{ $post->category ? $post->category->name_slug : '' }}"
                                date='{{ translate_date($post->created_at) }}'
                                title='{{ $post->title }}'
                                titleSlug='{{ $post->title_slug }}'
                                postId='{{ $post->id }}'
                            />
                        @endforeach
                        @foreach ($all_time_popular_posts as $post)
                            <x-card.type-one
                                image="{{ $post->preview_image }}"
                                category="{{ $post->category ? $post->category->name : '' }}"
                                categorySlug="{{ $post->category ? $post->category->name_slug : '' }}"
                                date='{{ translate_date($post->created_at) }}'
                                title='{{ $post->title }}'
                                titleSlug='{{ $post->title_slug }}'
                                postId='{{ $post->id }}'
                            />
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <div class="slot-2">
                {{-- Left Side --}}
                <livewire:components.home.update-post :class="'left-side'" />
                
                <div class="right-side">
                    <div class="follow-us-container">
                        <div class="title">Ikuti Kami</div>
                        <div class="icon-list">
                            <a href="#"><x-icon.fb/></a>
                            <a href="#"><x-icon.ig/></a>
                        </div>
                    </div>
                    <div class="ads-slot-1-container">
                        {{-- <img src="{{ asset('image\FIFA 2022.png') }}" alt="ads_alt"> --}}
                        <div class="no-ads">Ads Here!</div>
                    </div>
                    {{-- Most View --}}
                    <livewire:components.home.most-view-post />
                    
                    {{-- Top Categories --}}
                    <livewire:components.home.top-categories />

                </div>
            </div>   
        </div>
    </div>
</div>

@push('script')
    <script>
        //Swiper
        var swiper1 = new Swiper(".swiper-1", {
            slidesPerView: 4,
            spaceBetween: 4,
            slidesPerGroup: 4,
            loop: true,
            loopFillGroupWithBlank: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swipe-next-btn",
                prevEl: ".swipe-prev-btn",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                },
                450: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                },
                650: {
                    slidesPerView: 3,
                    slidesPerGroup: 3,
                },
                992: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                },
                1200: {
                    slidesPerView: 5,
                    slidesPerGroup: 5,
                },
            }
        });

        // Set Section Padding Based on Navbar Height
        const navbar = $('.navbar');

        // Animate Navbar Scroll
        const navLogo = $('#nav-logo');
        const navMenu = $('#nav-menu');
        const headerLogo = $('.header-logo-container');
        const navLogoShadow = $('#nav-logo-shadow');

        navLogo.addClass( "scroll-hidden" );
        navLogoShadow.css('width', 0);
        navLogoShadow.css('height', 0);
        navbar.css('background-color', 'rgba(28, 28, 28, 0.6)');

        function hideNavbarLogo(calcWidth, calcHeight, calcOpacity) {
            navLogo.addClass( "scroll-hidden" );
            navLogoShadow.css('width', calcWidth);
            navLogoShadow.css('height', calcHeight);
            navbar.css('background-color', 'rgba(28, 28, 28, '+calcOpacity+')');
        }

        function showNavbarLogo() {
            navLogo.removeClass( "scroll-hidden" );
            navLogoShadow.css('width', 0);
            navLogoShadow.css('height', 0);
            navbar.css('background-color', 'rgba(28, 28, 28, 1)');
        }
        
        if ($(window).scrollTop() >= headerLogo.offset().top) {
            showNavbarLogo();
        }
        else {
            hideNavbarLogo(0, 0, 0.6);
            $(window).scrollTop(0);
        }

        

        $(window).scroll(function() {            
            if (this.scrollY < headerLogo.offset().top) {
                var calcWidth = (this.scrollY / headerLogo.offset().top) * navLogo.width();
                var calcHeight = (this.scrollY / headerLogo.offset().top) * navLogo.height();

                var calcOpacity = (this.scrollY / headerLogo.offset().top) + 0.6;
                hideNavbarLogo(calcWidth, calcHeight, calcOpacity);
            }
            if (this.scrollY >= headerLogo.offset().top) {
                showNavbarLogo();
            }
        });

    </script>
@endpush