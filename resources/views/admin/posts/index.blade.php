@extends('layouts.app')

@section('title', 'Admin: Posts')

@section('content')
    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="small table-primary text-secondary">
            <tr>
                <th></th>
                <th></th>
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATED AT</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        <img src="{{ $post->image }}" alt="post id {{ $post->id }}"
                            style="width: 6rem; height: 6rem; object-fit: cover;">
                    </td>
                    <td>
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
                    </td>
                    <td class="fw-bold">{{ $post->user->name }}</td>
                    <td>{{ $post->created_at }}</td>
                    <td>
                        @if ($post->trashed())
                            <i class="fa-solid fa-circle-minus text-secondary"></i>&nbsp; Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            <div class="dropdown-menu">
                                @if ($post->trashed())
                                    <button class="dropdown-item text-primary" data-bs-toggle="modal"
                                        data-bs-target="#unhide-post-{{ $post->id }}">
                                        <i class="fa-solid fa-eye"></i> Unhide this post
                                    </button>
                                @else
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                        data-bs-target="#hide-post-{{ $post->id }}">
                                        <i class="fa-solid fa-eye-slash"></i> Hide this post
                                    </button>
                                @endif
                            </div>
                        </div>

                        @include('admin.posts.modal.status')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $all_posts->links() }}
    </div>
@endsection
