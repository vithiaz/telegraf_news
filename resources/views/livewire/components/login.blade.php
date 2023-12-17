<div wire:ignore.self class="modal fade" id="auth_modal" tabindex="-1">
    <div class="modal-dialog">
        <form wire:submit.prevent='login' class="modal-content">
            @csrf

            <button class="close-modal-button" onclick="hide_auth_modal()" data-bs-dismiss="modal">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="logo-wrapper">
                <img src="{{ asset('img\logo.png') }}" alt="{{ config('app.name_slug', 'news') }}_logo">
                <div class="brand">
                    <span class="brand-name">Telegraf</span>
                    {{-- <span class="page">Logo</span> --}}
                </div>
            </div>

            <div class="title">Masuk</div>
            <div class="form-floating">
                <input wire:model='email' type="email" class="form-control @error('email') is-invalid @enderror @if(Session::has('error')) is-invalid @endif" id="login_email" placeholder="email">
                <label for="login_email">Email</label>
                @error('email')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-floating">
                <input wire:model='password' type="password" class="form-control @error('password') is-invalid @enderror @if(Session::has('error')) is-invalid @endif" id="login_password" placeholder="password">
                <label for="login_password">Password</label>
                @error('password')
                <small class="error">{{ $message }}</small>
                @enderror
            </div>
            @if (Session::has('error'))
                <small class="error">{{ Session::get('error') }}</small>
            @endif
            <button type="submit" class="login-button">
                Masuk
            </button>
            <div class="seperator">
                <span>Masuk dengan</span>
            </div>
            <div class="social-ic-wrapper">
                <a href="#"><x-icon.google/></a>
                {{-- <a href="{{ route('google-redirect') }}"><x-icon.google/></a> --}}
            </div>
            <div class="register-suggest">
                <span>Belum punya akun?</span>
                {{-- marker! --}}
                <a href="#" class="register"> Buat Akun</a>
                {{-- <a href="{{ route('base-register') }}" class="register"> Buat Akun</a> --}}
            </div>
        </form>
    </div>
</div>

@push('script')
<script>

    function hide_auth_modal() {
        $('#auth_modal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        // $('.modal-backdrop').remove();
    }

</script>
@endpush