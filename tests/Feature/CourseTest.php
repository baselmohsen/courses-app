<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_create_course()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $token = $instructor->createToken('token')->plainTextToken;

        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/courses', [
                             'title'=>'My First Course',
                             'description'=>'Course Description',
                             'price'=>100,
                             'category_id'=>$category->id
                         ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title'=>'My First Course']);

        $this->assertDatabaseHas('courses',['title'=>'My First Course']);
    }

    public function test_student_cannot_create_course()
    {
        $student = User::factory()->create(['role' => 'student']);
        $token = $student->createToken('token')->plainTextToken;

        $instructor = User::factory()->create(['role'=>'instructor']);
        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/courses', [
                             'title'=>'Hack Course',
                             'description'=>'Desc',
                             'price'=>50,
                             'category_id'=>$category->id,
                            'instructor_id' => $instructor->id,

                         ]);

        $response->assertStatus(403);
    }

    public function test_anyone_can_view_courses()
    {
        $course = Course::factory()->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title'=>$course->title]);
    }
}
