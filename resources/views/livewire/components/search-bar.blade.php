<div class="search-bar">
    <form wire:submit.prevent='input_search' class="input-group">
        <input wire:model="search" type="text" class="form-control" placeholder="Cari berita ...">
        <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    @push('hidden-search-bar')
        <div class="hidden-search-bar">
            <div class="container">
                <form action="javascript:setSearchInput();" class="input-group">
                    <input type="text" class="form-control" placeholder="Cari berita ...">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>
    @endpush
</div>

@push('script')
<script>

    function setSearchInput() {
        const searchVal =  $('.hidden-search-bar form input').val();
        @this.set('search', searchVal);
        @this.input_search();
    }

</script>
@endpush