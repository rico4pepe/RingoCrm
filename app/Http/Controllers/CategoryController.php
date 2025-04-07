<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //


    public function showCategoryForm(){

        return view('create-categories');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',

        ]);

        // Save in tickets table
        Category::create([

            'category_name' => $request->input('category'),

        ]);

        return back()->with('success', 'Category created successfully.');
    }


    public function update(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'category_name' => 'required|string|max:255',
    ]);

    // Find the category
    $category = Category::findOrFail($request->category_id);

    // Update the category
    $category->update([
        'category_name' => $request->category_name,
    ]);

    return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
}

public function destroy($categoryId)
{
    $category = Category::findOrFail($categoryId);
    
    // Delete the category
    $category->delete();

    return redirect()->route('category.view')->with('success', 'Category deleted successfully.');
}

    public function index()
    {
        // Fetch all categories
        $categories = Category::all();

        return view('view_category', compact('categories'));
    }
    public function showEditForm(Category $category)
    {
        // Show the edit form for a specific category
        return view('edit-category', compact('category'));
    }
}
