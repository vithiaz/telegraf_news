<div class="components popular-category-container">
    <div class="title">
        Kategori Terbanyak
    </div>
    <div class="card-view-wrapper">
        @foreach ($top_categories as $category)
            <div class="card-category">
                <div class="card-title">
                    {{-- marker! --}}
                    {{-- <a href="#">{{ $category->name }}</a> --}}
                    <a href="/category/{{ $category->name_slug }}">{{ $category->name }}</a>
                </div>
                <span class="number">{{ $category->posts->count() }}</span>
            </div>
        @endforeach
    </div>
</div>