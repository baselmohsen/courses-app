<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;

class CourseController extends Controller
{
    public function index()
    {
        return Course::with('instructor')->paginate(10);
    }

    public function store(CourseStoreRequest $request)
    {
        return Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price, 
            'instructor_id' => auth()->id(),
            'category_id' => $request->category_id,

        ]);
    }

    public function show($id)
    {
        return Course::with('lessons')->findOrFail($id);
    }

    public function update(CourseUpdateRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->validated());

        return $course;
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

