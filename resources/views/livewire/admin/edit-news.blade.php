@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-add-post-page.css') }}">
@endpush

<div class="add-news-page">
    <div class="container">
        <div class="content-card">
            <div class="content-title">
                <span>Ubah Postingan</span>
            </div>
            <div class="add-pages">
                <span class="input-name">Halaman</span>
                {{-- <span>{{ $post->page->name }}</span> --}}
            </div>
            <div id="main-input" class="main-input show" wire:ignore.self>
                <div class="input-title form-floating">
                    <input wire:model='title' type="text" class="form-control @error('title') is-invalid @enderror" id="input-post-title" placeholder="Judul">
                    <label for="input-post-title">Judul</label>
                    @error('title')
                    @php
                        $this->dispatchBrowserEvent('scroll-to-error', ['elemId' => 'input-post-title']);
                    @endphp
                        <small class="error" on="alert('Hello')">{{ $message }}</small>
                    @enderror
                </div>
                <div class="input-category">
                    <span class="input-title">Kategori</span>
                    <select wire:model='select_category' class="form-select" aria-label="post category">
                        <option value='0' selected hidden>Pilih Kategori</option>
                        <option value='0'>. . .</option>
                        @foreach ($selection_categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-profile-image">
                    <div
                        class="image-main-container"
                        x-data="{ isUploading: false, progress: 5}"
                        x-on:livewire-upload-start="isUploading = true; progress=5"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress; progress == 100 ? isUploading=false : null;"
                        >
                        @if ($image)
                            <div class="image-container">
                                @if ($ImgDeleteState)
                                    <img src="{{ $image->temporaryUrl() }}">
                                @else
                                    <img src="{{ asset('storage/'.$image) }}">
                                @endif
                                <div class="option-wrapper">
                                    <button class="remove-image-button">
                                        <i class="fa-solid fa-trash"></i>
                                        <span wire:click='empty_image()'>hapus</span>
                                    </button>
                                </div>
                            </div>
                        @else
                        <div class="no-image-container" :class="isUploading ? 'hide' : ''">
                            @error('image')
                                <br><small class="error" style="width: fit-content; padding-left: 0;">{{ $message }}</small>
                            @else
                                <span class="no-image-text">belum ada headline diupload</span>
                            @enderror
                            <input type="file" wire:model='image' id="input-image" accept="image/*">
                            <button class="input-image-button" onclick="$('#input-image').click()">Pilih Gambar</button>
                        </div>
                        @endif
                        
                        {{-- Progress Bar --}}
                        <div class="progress" :class="isUploading ? 'show' : ''">
                            <div class="progress-bar" role="progressbar" x-bind:style="`width: ${progress}%`" x-bind:aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100" x-text="progress"></div>
                        </div>
                    </div>
                    <div class="desc">
                        <span>* tambahkan foto/gambar untuk ditampilkan sebagai headline.</span>
                    </div>
                </div>
                <div class="input-info">
                    <div class="input-date">
                        <span class="input-name">Tanggal Berita</span>
                        <input wire:model='post_date' type="date" class="form-control">
                    </div>
                    <div class="input-location">
                        <span class="input-name">Lokasi</span>
                        <input wire:model='location' type="text" class="form-control" placeholder="lokasi berita">
                    </div>
                </div>
                <div class="input-body-news" wire:ignore>
                    <div id="body-news-editor">{!! $post->body !!}</div>
                </div>
                <div class="button-wrapper">
                    <button class="delete-button" data-bs-toggle="modal" data-bs-target="#deletePostModal">Hapus</button>
                    <button onclick="location.href='{{ route('admin-news-list') }}'" class="abort-button">Batalkan</button>
                    <button wire:click='validate_data' class="save-button">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Post Confirmation Modal --}}
    <div wire:ignore.self class="confirmation-modal modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span><b>Simpan Perubahan?</b></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-button abort-button" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="modal-button confirm-button" wire:click='store_data'>Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Post Confirmation Modal --}}
    <div wire:ignore.self class="confirmation-modal modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span><b>Hapus Postingan?</b></span>
                    <br><span>Semua data terkait postingan ini akan dihapus permanen</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-button abort-button" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="modal-button alert-button" wire:click='delete_post'>Hapus</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('script')
<script>

    // Open Add Post Modal
    $(window).on('toggle-add-post-modal', function () {        
        $('#addPostModal').modal('toggle');
    });


    $(window).on('scroll-to-error', function (e) {
        $( [document.documentElement, document.body] ).animate({
            scrollTop: $(`#${e.detail.elemId}`).offset().top
        }, 50);
    });
    
    // Summernote
    $(document).ready(function () {

        $('#body-news-editor').summernote({
            placeholder: "Berita ...",
            minHeight: 250,

            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontname', 'strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['picture', 'link']],
                ['misc', ['undo', 'redo']],
            ],
            callbacks: {
                onChange: (contents, $editable) => {
                    @this.set('body', contents);
                }
            },
        });

        $('.dropdown-toggle').dropdown();
    });

    // Summernote Toolbar Adapt
    function toolbar_adapt() {
        if ($('.note-toolbar').length > 0) {
            if ( $(this).scrollTop() >= $('.note-editor').offset().top) {
                $('.note-toolbar').width($('.note-editor').width() - 4);
                $('.note-toolbar').addClass('floating');
                $('.note-editing-area').css('margin-top', $('.note-toolbar').height()+'px');
            } else {
                $('.note-toolbar').width('unset');
                $('.note-toolbar').removeClass('floating');
                $('.note-editing-area').css('margin-top', '0');
            }
        }
    }
    // Summernote Dropdown Menu
    function close_dropdown() {
        $('.note-dropdown-menu').removeClass('show');
        $.each($('button.dropdown-toggle'), function () {
            $( this ).removeClass('show');
        });
    }


    $(window).scroll(function () {
        
        toolbar_adapt();
        close_dropdown();
    });

    $(window).resize(function() {
        toolbar_adapt();
        close_dropdown();
    });
        
    
    

</script>    
@endpush