@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-category-page.css') }}">
@endpush

<div class="admin-category-page">
    <div class="container">
        <div class="content-card">
            <div class="content-header">
                <div class="content-title">
                    <span>Kategori</span>
                </div>
            </div>
            <div class="add-category-container">
                <span class="title">Tambahkan Kategori</span>
                <form wire:submit.prevent='validate_category' class="input-group">
                    <input wire:model='new_category' type="text" class="form-control" id="input-category-name" placeholder="Nama Kategori">
                    <button type="submit" id="add-category-button" class="add-button">Tambahkan</button>
                </form>
            </div>
            <div class="list-category-head">
                <span class="title">Daftar Kategori</span>
                <div class="content-search">
                    <input type="text" placeholder="Cari kategori">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
            <div class="content-body table-responsive-md">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Nama Kategori</td>
                            <td>Jumlah Postingan</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $count=>$category)
                            <tr>
                                <td>{{ $count + 1 }}</td>
                                <td class="category-{{ $category->id }}-name">{{ $category->name }}</td>
                                <td>{{ $category->posts()->count() }}</td>
                                <td>
                                    <a onclick="confirm_delete($(this))" class="delete-post-button" data-category-id="{{ $category->id }}" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">Hapus</a>
                                    {{-- <a class="delete-post-button" data-category-id="{{ $category->id }}" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">Hapus</a> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="empty" colspan="4">Belum ada kategori . . .</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Add Category Confirmation Modal --}}
    <div wire:ignore.self class="confirmation-modal modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Tambahkan <span class="confirmation-input">{{ $new_category }}</span> ke <span class="confirmation-page" data-page-id='{{ $select_page }}'></span>?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-button abort-button" data-bs-dismiss="modal">Batalkan</button>
                    <button wire:click='store_category' type="button" class="modal-button confirm-button">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Category Confirmation Modal --}}
    <div wire:ignore class="confirmation-modal modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Hapus Kategori <span class="confirmation-input"></span>?</span>
                    <span>Semua Postingan dalam kategori ini juga akan terhapus.</span>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-button abort-button" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" wire:click='delete_category' class="modal-button confirm-button">Hapus</button>
                </div>
            </div>
        </div>
    </div>

</div>


@push('script')
<script>

    $(window).resize(function () {
        
        if( $(this).width() <= 767 ) {
            $('#add-category-button').text('');
            $('#add-category-button').append("<i class='fa-solid fa-plus'></i>");
        }
        else {
            $('#add-category-button').text('Tambahkan');
            $('#add-category-button').remove('i');
        }

    });

    // Open Add Category Modal
    $(window).on('toggle-add-category-modal', function () {
        // Change Page Destination on Confirmation Modal
        $selected_page_name = $('#select_page').children('option:selected').text();
        $('.confirmation-modal .confirmation-page').text($selected_page_name);
        
        $('#addCategoryModal').modal('toggle');
    });


    // Open Delete Category Modal
    function confirm_delete(elem) {
        const categoryId = elem.attr('data-category-id');
        const categoryName = $('.category-'+categoryId+'-name').text();

        console.log(categoryId);
        console.log(categoryName);
        
        $('#deleteCategoryModal .confirmation-input').text(categoryName);
        $('#deleteCategoryModal').modal('toggle');
        
        @this.set('delete_id', categoryId);
    }
    $(window).on('hide-delete-category-modal', function () {
        $('#deleteCategoryModal button.abort-button').click();
    });

</script>
@endpush