<div class="content-comments">
    <div class="title">
        Komentar
    </div>

    {{-- Comments Section --}}
    @auth
        <div class="user-profile">
            <div class="image-container">
                @if (Auth::user()->profile_img)
                    <img src="{{ asset('storage/'.Auth::user()->profile_img) }}" alt="{{ Auth::user()->id . '-profile' }}">                
                @else
                    <div class="no-image">
                        <i class="fa-solid fa-user"></i>
                    </div>
                @endif
            </div>
            <span class="username">{{ Auth::user()->first_name }}</span>
        </div>
        <form wire:submit.prevent='store_comment' class="add_comment">
            <textarea wire:model='comment' class="form-control" rows="3"></textarea>
            <button type="submit">Tambahkan Komentar</button>
        </form>
    @endauth

    <div class="comments-wrapper">
        @forelse ($comments as $comment)
            <div class="user-comment">
                <div class="user-profile">
                    <div class="image-container">
                        @if ($comment->user->profile_img)
                            <img src="{{ asset('storage/'.$comment->user->profile_img) }}" alt="{{ $comment->user->id . '-profile' }}">                
                        @else
                            <div class="no-image">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <span class="username">{{ $comment->user->first_name }}</span>
                </div>
                <div class="comment-text-wrapper">
                    <div class="comment-text">
                        <p>{{ $comment->comment }}</p>
                    </div>


                    @auth                        
                        @if (Auth::user()->user_type != 1)
                            @if (Auth::user()->id == $comment->user->id)
                                <div class="action-button-container">
                                    <div onclick="confirm_delete({{ $comment->id }})" class="action-button">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="action-button-container">
                                <div onclick="confirm_delete({{ $comment->id }})" class="action-button">
                                    <i class="fa-solid fa-trash"></i>
                                </div>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>
        @empty
            <div class="empty-comment">
                <span>Belum ada komentar pada postingan ini!</span>
            </div>
        @endforelse
    </div>

    {{-- Delete Comment Confirmation Modal --}}
    <div wire:ignore.self class="confirmation-modal modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Hapus komentar ini?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-button abort-button" data-bs-dismiss="modal">Batalkan</button>
                    <button wire:click="delete_comment" type="button" class="modal-button alert-button">Hapus</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('script')
<script>

    // Open Delete Category Modal
    function confirm_delete(id) {
        $('#deleteCommentModal').modal('toggle');
        @this.set('delete_id', id);
    }
    $(window).on('hide-delete-comment-modal', function () {
        $('#deleteCommentModal button.abort-button').click();
    });

</script>
@endpush