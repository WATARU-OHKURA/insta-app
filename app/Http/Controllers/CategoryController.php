<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:50',
        ]);

        $this->category->name = $request->name;
        $this->category->save();

        return redirect()->route('admin.categories');
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:2|max:50',
        ]);

        $category = $this->category->findOrFail($id);

        $category->name = $request->name;
        $category->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $category = $this->category->findOrFail($id);
        $category->forceDelete($id);

        return redirect()->back();
    }
}
