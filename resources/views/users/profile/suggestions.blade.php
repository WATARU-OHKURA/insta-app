@extends('layouts.app')

@section('title', 'Following')
{{-- @dd($user->following) --}}
@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-4">

                @if ($suggested_users)
                    <h3 class="text-muted text-center mb-3">Suggestions for you</h3>
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
                @else
                    <h3 class="text-muted text-center">No Suggestions</h3>
                @endif

            </div>
        </div>
    </div>
@endsection
