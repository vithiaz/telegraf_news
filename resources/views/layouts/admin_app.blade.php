<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        @include('layouts.inc.head')
        <link rel="stylesheet" href="{{ asset('css/admin_app.css') }}">
    </head>
    <body>
        <main>
            <div class="admin-layout"
                data-notify='{{ session('message') }}'
                >

                @include('layouts.inc.admin-sidebar')
                <div class="layout-content">
                    @include('layouts.inc.admin-navbar')
                    {{ $slot }}
                </div>
            </div>
            @stack('modal-stack')
        </main>

        {{-- Session message --}}
        @include('components.session_message')
    
        @include('layouts.inc.tail')
    </body>
</html>
