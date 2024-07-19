{{-- Hide --}}
<div class="modal fade" id="hide-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h5 modal-title text-danger">
                    <i class="fa-solid.fa-user-slash"></i> Hide Post
                </h3>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to hide this post?</p>

                <div>
                    <img src="{{ $post->image }}" alt="post id {{ $post->id }}"
                        style="width: 8rem; height: 8rem; object-fit: cover;">
                </div>

                <div>
                    {{ $post->description }}
                </div>

            </div>

            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.hide', $post->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-danger btn-sm">Hide</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Unhide --}}
<div class="modal fade" id="unhide-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header border-primary">
                <h3 class="h5 modal-title text-primary">
                    <i class="fa-solid.fa-user-check"></i> Unhide Post
                </h3>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to unhide this post?</p>

                <div>
                    <img src="{{ $post->image }}" alt="post id {{ $post->id }}"
                        style="width: 8rem; height: 8rem; object-fit: cover;">
                </div>

                <div>
                    {{ $post->description }}
                </div>
            </div>


            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.unhide', $post->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="button" class="btn btn-outline-primary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-primary btn-sm">Unhide</button>
                </form>
            </div>
        </div>
    </div>
</div>
