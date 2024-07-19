@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div style="width: 30rem" class="mx-auto">
    <form action="{{ route('password.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label for="currentPassword" class="form-label">Enter Current Password</label>
            <input type="password" name="currentPassword" id="currentPassword" class="form-control">
        </div>

        <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" name="newPassword" id="newPassword" class="form-control">
        </div>

        <div class="mb-4">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" name="newPassword_confirmation" id="confirmPassword" class="form-control">
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
            <button type="submit" class="btn btn-primary w-100">Change</button>
        </div>
    </form>
</div>
@endsection
