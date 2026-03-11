<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.addcategory');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        Category::create([
            'category_name' => $request->category_name,
        ]);

        return redirect()->route('admin.addcategory')->with('success', 'Category added!');
    }

    public function viewcategory()
    {
        $categories = Category::all();
        return view('admin.viewcategory', compact('categories'));
    }
}