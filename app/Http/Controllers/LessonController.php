<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Requests\LessonStoreRequest;

class LessonController extends Controller
{
    public function store(LessonStoreRequest $request)
    {
        return Lesson::create($request->validated());
    }

    public function destroy($id)
    {
        Lesson::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

