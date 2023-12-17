<nav class="navbar news-navbar">
    <div class="container">
        <div id="nav-logo" class="logo">
            <img src="{{ asset('img\logo.png') }}" alt="{{ config('app.name_slug', 'news') }}_logo">
            <div class="brand">
                <span class="brand-name">TELEGRAF</span>
                <span class="page">Today</span>
                <i class="fa-sharp fa-solid fa-angle-down"></i>
            </div>
        </div>
        <div id="nav-logo-shadow"></div>
        <div id="nav-menu" class="menu">
            <span><a href="{{ route('homepage') }}">Beranda</a><div class="line"></div></span>
            <span><a href="{{ route('news-page') }}">Berita</a><div class="line"></div></span>
            <span><a href="{{ route('gallery') }}">Gallery</a><div class="line"></div></span>
            <span class="search-menu-hidden">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
        </div>

        <livewire:components.search-bar />
        
        {{-- Auth Menu --}}
        <div class="auth">
            @auth
                <div class="logged-in">
                    <span class="username">
                        {{ Auth::user()->first_name }}
                    </span>
                    <i id="auth-dropdown-button" class="fa-sharp fa-solid fa-angle-down arrow-head"></i>

                    <div class="dropdown">
                        <ul>
                            @if (Auth::user()->user_type == '1')
                                <li>
                                    <i class="fa-solid fa-toolbox"></i>
                                    <a href="{{ route('admin-news-list') }}">Admin Panel</a>
                                </li>
                            @endif
                            {{-- marker! --}}
                            {{-- <li>
                                <i class="fa-regular fa-address-card"></i>
                                <a href="{{ route('user-settings') }}">Pengaturan Akun</a>
                            </li> --}}
                            <li>
                                <i class="fa-solid fa-door-open"></i>
                                <a href="{{ route('global-logout') }}">Keluar</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
            @else
                {{-- marker! --}}
                {{-- <span class="register" onclick="location.href='{{ route('base-register') }}'">Daftar</span> --}}
                <span class="login" data-bs-toggle="modal" data-bs-target="#auth_modal">Masuk</span>
                <span class="login-hidden" data-bs-toggle="modal" data-bs-target="#auth_modal"><i class="fa-solid fa-right-to-bracket"></i></span>
            @endauth
        </div>
        
    </div>
    <div class="nav-logo-dropdown">
        <ul>
            <li><a href="{{ route('homepage') }}">Telagraf <span class="sport"></span></a></li>
        </ul>
    </div>

    {{-- Hidden Search Bar --}}
    @stack('hidden-search-bar')

        
</nav>

<!-- Modal -->
<livewire:components.login />

@push('script')
<script>

    // Search Icon Click
    $('#nav-menu .search-menu-hidden').click(function () {
        $('.hidden-search-bar').toggleClass('appear');
        remove_nav_logo_dropdown();
        $('#nav-menu .search-menu-hidden i').toggleClass('fa-magnifying-glass');
        $('#nav-menu .search-menu-hidden i').toggleClass('fa-magnifying-glass-minus');
    });

    var checkWidth = () => {
        if($(window).width() <= 770) {
                $(".menu").before($(".auth"));
            }
        else {
            $(".search-bar").after($(".auth"));
        }

        $('.nav-logo-dropdown').css('left', $('#nav-logo').offset().left);
        $('.nav-logo-dropdown').css('top', ( $('.navbar').height() ) );
        $('.hidden-search-bar').css('top', ( $('.navbar').height() ));
    }
    checkWidth();
    
    function toggle_nav_logo() {
        $('.nav-logo-dropdown').toggleClass( "active" );
        $('#nav-logo .brand i').toggleClass( "rotate" );
    }

    function remove_nav_logo_dropdown() {
        if ($('.nav-logo-dropdown').hasClass( "active" ) && $('#nav-logo .brand i').hasClass( "rotate" ) ) {
            $('.nav-logo-dropdown').removeClass( "active" );
            $('#nav-logo .brand i').removeClass( "rotate" );
        }
    }

    function remove_hidden_searchbar() {
        if ($('.hidden-search-bar').hasClass('appear')) {
            $('.hidden-search-bar').removeClass('appear');
        }
        $('#nav-menu .search-menu-hidden i').addClass('fa-magnifying-glass');
        $('#nav-menu .search-menu-hidden i').removeClass('fa-magnifying-glass-minus');

    }

    function scroll_up() {
        $('.navbar').addClass('scroll-up');
        $('.navbar').removeClass('scroll-down');
        $('.hidden-search-bar').css('top', ( $('.navbar').height() ));
    }
    
    function scroll_down() {
        $('.navbar').addClass('scroll-down');
        $('.navbar').removeClass('scroll-up');
        $('.hidden-search-bar').css('top', ( $('.navbar').height() ));
    }

    // Resize Event
    $(window).resize(function () {
        checkWidth();
        remove_nav_logo_dropdown();
        remove_hidden_searchbar();
        remove_nav_logo_dropdown();
        closeAuthDropdown();
    });

    // Nav Logo Clicked
    $('#nav-logo').click(function () {
        toggle_nav_logo();
        remove_hidden_searchbar();
    });
    
    // Scrolling Events
    $(window).scroll(function () {
        remove_nav_logo_dropdown()
        remove_hidden_searchbar();
        closeAuthDropdown();

        $('.nav-logo-dropdown').css('left', $('#nav-logo').offset().left);
        $('.nav-logo-dropdown').css('top', ( $('.navbar').height() ) );
        $('.hidden-search-bar').css('top', ( $('.navbar').height() ));
    });


    if ($('#home-content-nav').length != 0) {
        let lastScroll = 0;

        $(window).scroll(function () {
            if (this.scrollY <= 0) {
                $('.navbar').removeClass('scroll-up');
                $('.hidden-search-bar').css('top', ( $('.navbar').height() ));
            }
            if (this.scrollY > $('#home-content-nav').offset().top) {
                if (this.scrollY > lastScroll && !$('.navbar').hasClass('scroll-down')) {
                    scroll_down()
                }
                if (this.scrollY <= lastScroll && $('.navbar').hasClass('scroll-down')) {
                    scroll_up();
                }
                lastScroll = this.scrollY;
            }
        });
    }
    else {
        let lastScroll = 0;
    
        $(window).scroll(function () {
            if (this.scrollY <= 0) {
                $('.navbar').removeClass('scroll-up');
                $('.hidden-search-bar').css('top', ( $('.navbar').height() ));
            }
            if (this.scrollY > $('.navbar').height()) {
                if (this.scrollY > lastScroll && !$('.navbar').hasClass('scroll-down')) {
                    scroll_down();
    
                }
                if (this.scrollY <= lastScroll && $('.navbar').hasClass('scroll-down')) {
                    scroll_up();
                }
            }

            lastScroll = this.scrollY;
        });
    }


    // Auth Dropdown
    let AdaptDropdownLoc = () => {
        if ($('.auth .logged-in').length > 0) {
            const Navbar = $('.navbar');
            const Dropdown = $('.auth .logged-in .dropdown');
            const DropdownTriggerBtn = $('#auth-dropdown-button');
            const btn_offset = DropdownTriggerBtn.offset().left;

            Dropdown.css('left', btn_offset - Dropdown.width() + DropdownTriggerBtn.width() + 20);
            Dropdown.css('top', Navbar.height());
        }
    }

    let closeAuthDropdown = () => {
        $('#auth-dropdown-button').removeClass('active');
        $('.auth .logged-in .dropdown').removeClass('active');
    }

    $('#auth-dropdown-button').click(function () {
        $(this).toggleClass('active');
        $('.auth .logged-in .dropdown').toggleClass('active');
        AdaptDropdownLoc();
    });

    $('.auth .logged-in .username').click(function () {
        $('#auth-dropdown-button').toggleClass('active');
        $('.auth .logged-in .dropdown').toggleClass('active');
        AdaptDropdownLoc();
    });


        
</script>
@endpush