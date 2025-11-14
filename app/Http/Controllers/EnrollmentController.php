<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Requests\EnrollmentRequest;

class EnrollmentController extends Controller
{
    public function store(EnrollmentRequest $request)
    {
        return Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $request->course_id
        ]);
    }
}
