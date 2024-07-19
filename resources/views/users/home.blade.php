@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row gx-5">
        <div class="col-8">
            @forelse ($home_posts as $post)
                <div class="card mb-4">
                    @include('users.posts.contents.title')
                    @include('users.posts.contents.body')
                </div>
            @empty
                {{-- If the site doesn't have any post yet. --}}
                <div class="text-center">
                    <h2>Share Photos</h2>
                    <p class="text-muted">When you share photos, they'll appear on your profile</p>

                    <a href="{{ route('post.create') }}" class="text-decoration-none">Share your first photo</a>
                </div>
            @endforelse
        </div>
        <div class="col-4">
            {{-- Profile Overview --}}
            <div class="row align-items-center mb-5 bg-white shadow-sm rouded-3 py-3">
                <div class="col-auto">
                    <a href="{{ route('profile.show', Auth::user()->id) }}">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                class="rounded-circle avatar-md">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                        @endif
                    </a>
                </div>
                <div class="col ps-0">
                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-decoration-none text-dark fw-bold">
                        {{ Auth::user()->name }}
                    </a>
                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                </div>
            </div>

            {{-- Seggeted posts --}}
            @if ($popularPosts)
                <div class="row">
                    <div class="col-auto">
                        <p class="fw-bold text-secondary">Popular Posts</p>
                    </div>
                </div>

                <div class="row mb-3">
                    @foreach ($popularPosts as $post)
                        <div class="col-auto">
                            <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none">
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="{{ $post->image }}" alt="{{ $post->id }}" class="card-img-top">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $post->user->name }}</h5>
                                                <p class="card-text">{{ $post->description }}</p>
                                                <p class="card-text">
                                                    <small class="text-muted">{{ $post->likes_count }} likes</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Suggested users --}}
            @if ($suggested_users)
                <div class="row">
                    <div class="col-auto">
                        <p class="fw-bold text-secondary">Suggestions for you</p>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('suggest', Auth::user()->id) }}"
                            class="fw-bold text-dark text-decoration-none">See all</a>
                    </div>
                </div>

                @foreach ($suggested_users as $user)
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $user->id) }}">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                        class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>

                        <div class="col ps-0 text-truncate">
                            <a href="{{ route('profile.show', $user->id) }}"
                                class="text-decoration-none text-dark fw-bold">
                                {{ $user->name }}
                            </a>
                        </div>

                        <div class="col-auto">
                            <form action="{{ route('follow.store', $user->id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
