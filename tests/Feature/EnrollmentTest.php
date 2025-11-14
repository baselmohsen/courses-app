<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_enroll_course()
    {
        $student = User::factory()->create(['role'=>'student']);
        $token = $student->createToken('token')->plainTextToken;

        $course = Course::factory()->create();

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/enroll', ['course_id'=>$course->id]);

        $response->assertStatus(201); // لو الـ controller بيرجع 201
    }

    public function test_non_student_cannot_enroll()
    {
        $instructor = User::factory()->create(['role'=>'instructor']);
        $token = $instructor->createToken('token')->plainTextToken;

        $course = Course::factory()->create();

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/enroll', ['course_id'=>$course->id]);

        $response->assertStatus(403);
    }
}
