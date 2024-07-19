<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $all_categories = $this->category->withCount('categoryPost')->orderBy('id', 'desc')->paginate(10);

        $uncategorised_count = Post::doesntHave('categoryPost')->count();

        return view('admin.categories.index')->with([
            'all_categories' => $all_categories,
            'uncategorised_count' => $uncategorised_count
        ]);
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
}
