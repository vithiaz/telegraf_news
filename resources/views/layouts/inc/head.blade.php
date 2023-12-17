<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

{{-- Boostrap5 CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

{{-- Font Awesome --}}
<link href="{{ asset('/vendor/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/fontawesome/css/brands.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/fontawesome/css/solid.css') }}" rel="stylesheet">

{{-- SwiperJS CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/> --}}

{{-- Summernote CSS and JS --}}
<link rel="stylesheet" href="{{ asset('/vendor/summernote/summernote-bs4.css') }}">

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('stylesheet')
@livewireStyles