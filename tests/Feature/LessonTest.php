<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_create_lesson()
    {
        $instructor = User::factory()->create(['role'=>'instructor']);
        $token = $instructor->createToken('token')->plainTextToken;

        $course = Course::factory()->create(['instructor_id'=>$instructor->id]);

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/lessons', [
                             'title'=>'Lesson 1',
                             'course_id'=>$course->id,
                             'content'=>'Lesson Content'
                         ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title'=>'Lesson 1']);
    }
}
