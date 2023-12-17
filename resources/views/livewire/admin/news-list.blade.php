@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-news-list-page.css') }}">
@endpush

<div class="news-list-page">
    <div class="container">
        <div class="content-card">
            <div class="content-header">
                <div class="content-title">
                    <span>Daftar Berita</span>
                </div>
            </div>
            <div class="content-body">
                <livewire:admin.newst-list-table />
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>

    </script>
@endpush