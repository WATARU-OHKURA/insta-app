@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
    <div class="w-75">
        <form action="{{ route('admin.category.store') }}" method="POST" class="d-flex gap-2 mb-3">
            @csrf

            <div class="w-50">
                <input type="text" name="name" class="form-control" placeholder="Add a category"
                    value="{{ old('category') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Add
                </button>
            </div>
        </form>

        <table class="table table-hover align-middle bg-white border text-secondary">

            <thead class="small table-warning text-secondary">
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th>COUNT</th>
                    <th>LAST UPDATED</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all_categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->category_post_count }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#edit-category-{{ $category->id }}"><i
                                    class="fa-solid fa-pen"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}"><i class="fa-solid fa-trash"></i></button>
                        </td>

                        @include('admin.categories.modal.edit_delete')
                    </tr>
                @endforeach
                    <tr>
                        <td></td>
                        <td class="fw-bold">Uncategorised</td>
                        <td class="fw-bold">{{ $uncategorised_count }}</td>
                        <td></td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $all_categories->links() }}
    </div>
@endsection
