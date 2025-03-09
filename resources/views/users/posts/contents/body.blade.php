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
                <form action="{{ route('like.destroy', $post->id) }}" method="POST" class="like-form"
                    data-post-id="{{ $post->id }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm shadow-none p-0 like-button">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="POST" class="like-form"
                    data-post-id="{{ $post->id }}">
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
    <a href="{{ route('profile.show', $post->user->id) }}"
        class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
    &nbsp;
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- Include comments here --}}
    @include('users.posts.contents.comments')
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Echoが利用可能な場合、リアルタイムのLike更新をリッスン
        if (typeof Echo !== 'undefined') {
            const postId = '{{ $post->id }}';
            Echo.channel(`post.${postId}`)
                .listen('LikeUpdated', (event) => {
                    const likesCountElem = document.querySelector(`.likes-count-${event.post.id}`);
                    if (likesCountElem) {
                        likesCountElem.innerText = event.post.likes_count;
                    }
                });
        }

        /**
         * Likeボタンの表示状態を更新する
         * @param {HTMLFormElement} form - 対象のフォーム
         * @param {Object} response - axiosのレスポンスオブジェクト
         * @param {String} method - 現在のHTTPメソッド（'POST' or 'DELETE'）
         */
        const updateLikeButton = (form, response, method) => {
            const postId = form.getAttribute('data-post-id');
            const likesCountElem = document.querySelector(`.likes-count-${postId}`);
            if (likesCountElem) {
                likesCountElem.innerText = response.data.likes_count;
            }

            const heartIcon = form.querySelector('i');
            if (method === 'DELETE') {
                // いいね解除：アイコンを空ハートに戻し、フォームの状態を初期化
                heartIcon.classList.replace('fa-solid', 'fa-regular');
                heartIcon.classList.remove('text-danger');
                form.action = `{{ route('like.store', $post->id) }}`;
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }
            } else {
                // いいね追加：アイコンを実体ハートに切り替え、フォームのアクションを削除用に変更
                heartIcon.classList.replace('fa-regular', 'fa-solid');
                heartIcon.classList.add('text-danger');
                if (!form.querySelector('input[name="_method"]')) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = '_method';
                    hiddenInput.value = 'DELETE';
                    form.appendChild(hiddenInput);
                }
                form.action = `{{ route('like.destroy', $post->id) }}`;
            }
        };

        // 各Likeフォームに対して非同期送信のイベントリスナーを設定
        document.querySelectorAll('.like-form').forEach(form => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const method = formData.get('_method') || 'POST';
                const url = form.action;
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true; // 重複送信防止

                try {
                    const response = await axios({
                        method,
                        url,
                        data: formData
                    });
                    updateLikeButton(form, response, method);
                } catch (error) {
                    console.error('Like更新エラー:', error);
                } finally {
                    submitButton.disabled = false;
                }
            });
        });
    });
</script>
