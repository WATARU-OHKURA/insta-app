@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
        .col-4 {
            overflow-y: scroll
        }

        .card-body {
            position: absolute;
            top: 65px;
        }
    </style>

    <div class="row border shadow">
        <div class="col p-0 border-end">
            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
        </div>

        <div class="col-4 px-0 bg-white">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $post->user->id) }}">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}"
                                        class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>

                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user->id) }}"
                                class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                        </div>

                        <div class="col-auto">
                            @if (Auth::user()->id === $post->user->id)
                                <div class="dropdown">
                                    <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </a>

                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#delete-post-{{ $post->id }}">
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                    @include('users.posts.contents.modals.delete')
                                </div>
                            @else
                                @if ($post->user->isFollowed())
                                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $post->user->id) }}" method="POST">
                                        @csrf

                                        <button type="submit"
                                            class="border-0 bg-transparent p-0 text-primary">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body w-100">
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

                    <a href="{{ route('profile.show', $post->user->id) }}"
                        class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $post->description }}</p>
                    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

                    <div class="mt-4">
                        <form action="{{ route('comment.store', $post->id) }}" method="POST">
                            @csrf

                            <div class="input-group">
                                <textarea name="comment_body{{ $post->id }}" rows="1" class="form-control form-control-sm"
                                    placeholder="Add a comment...">{{ old('comment_body' . $post->id) }}</textarea>

                                <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                            </div>
                            @error('comment_body' . $post->id)
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </form>

                        @if ($post->comments->isNotEmpty())
                            <ul class="list-group mt-2">
                                @foreach ($post->comments as $comment)
                                    <li class="list-group-item border-0 p-0 mb-2">
                                        <a href="{{ route('profile.show', $comment->user->id) }}"
                                            class="text-decoration-none text-dark fw-bold">
                                            {{ $comment->user->name }}
                                        </a>
                                        &nbsp;
                                        <p class="d-inline fw-light">{{ $comment->body }}</p>

                                        <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <span
                                                class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                                            @if (Auth::user()->id === $comment->user->id)
                                                &middot;

                                                <button type="submit"
                                                    class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                            @endif
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
                        submitButton.disabled = false;
                    });
                });
            });
        });
    </script>
@endsection
