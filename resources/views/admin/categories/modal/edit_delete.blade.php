{{-- Edit --}}
<div class="modal fade" id="edit-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-warning">
            <div class="modal-header border-warning mb-3">
                <h3 class="h5 modal-title text-warning">
                    <i class="fa-solid fa-pen"></i> Edit Category
                </h3>
            </div>

            <div class="modal-footer border-0 justify-content-center mb-4">
                <form action="{{ route('admin.category.edit', $category->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')

                    <div class="w-75">
                        <input type="text" name="name" class="form-control" placeholder="Add a category"
                            value="{{ old('category', $category->name) }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete --}}
<div class="modal fade" id="delete-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger mb-3">
                <h3 class="h5 modal-title text-danger">
                    <i class="fa-solid fa-pen"></i> Delete Category
                </h3>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete {{ $category->name }}?</p>

            </div>

            <div class="modal-footer border-0">
                <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
