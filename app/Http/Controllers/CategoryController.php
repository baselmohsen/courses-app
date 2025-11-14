<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('courses')->get();
    }

    public function store(CategoryStoreRequest $request)
    {
        return Category::create($request->validated());
    }

    public function show($id)
    {
        return Category::with('courses')->findOrFail($id);
    }

    public function update(CategoryStoreRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return $category;
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(["message" => "Deleted"]);
    }
}
