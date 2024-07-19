{{-- Clickable image --}}
<div class="container p-9">
    <a href="{{ route('post.show', $post->id) }}">
        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
    </a>
</div>
<div class="card-body">
    {{-- heart button + no, of likes + categories --}}
    <div class="row align-items-center">
        <div class="col-auto">
            @if ($post->isLiked())
                <form action="{{ route('like.destroy', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm shadow-none p-0 like-button">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="POST" class="like-form" data-post-id="{{ $post->id }}">
                    @csrf

                    <button type="submit" class="btn btn-sm shadow-none p-0 like-button">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </form>
            @endif
        </div>

        <div class="col-auto px-0">
            <span class="likes-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
        </div>

        <div class="col text-end">
            @forelse ($post->categoryPost as $category_post)
                @if ($category_post->category && $category_post->category->id)
                    <div class="badge bg-secondary bg-opacity-50">
                        {{ $category_post->category->name }}
                    </div>
                @endif
            @empty
                <div class="badge bg-dark">
                    Uncategorised
                </div>
            @endforelse
        </div>
    </div>

    {{-- owner + description --}}
    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
    &nbsp;
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- Include comments here --}}
    @include('users.posts.contents.comments')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Echo !== 'undefined') {
            Echo.channel('post.' + {{ $post->id }})
                .listen('LikeUpdated', (e) => {
                    document.querySelector('.likes-count-' + e.post.id).innerText = e.post.likes_count;
                });
        }

        document.querySelectorAll('.like-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                let postId = this.getAttribute('data-post-id');
                let formData = new FormData(this);
                let method = formData.get('_method') || 'POST';
                let url = this.action;
                let submitButton = this.querySelector('button[type="submit"]');

                // ボタンを無効化
                submitButton.disabled = true;

                axios({
                    method: method,
                    url: url,
                    data: formData
                }).then(response => {
                    document.querySelector('.likes-count-' + postId).innerText = response.data.likes_count;
                    let heartIcon = this.querySelector('i');
                    if (method === 'DELETE') {
                        heartIcon.classList.remove('fa-solid', 'fa-heart', 'text-danger');
                        heartIcon.classList.add('fa-regular', 'fa-heart');
                        this.action = `{{ route('like.store', $post->id) }}`;
                        const methodInput = this.querySelector('input[name="_method"]');
                        if (methodInput) {
                            methodInput.remove();
                        }
                    } else {
                        heartIcon.classList.remove('fa-regular', 'fa-heart');
                        heartIcon.classList.add('fa-solid', 'fa-heart', 'text-danger');
                        let hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', '_method');
                        hiddenInput.setAttribute('value', 'DELETE');
                        this.appendChild(hiddenInput);
                        this.action = `{{ route('like.destroy', $post->id) }}`;
                    }
                }).catch(error => {
                    console.error('Error:', error);
                }).finally(() => {
                    // ボタンを再度有効化
                    submitButton.disabled = false;
                });
            });
        });
    });
</script>
