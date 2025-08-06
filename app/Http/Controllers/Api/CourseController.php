<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Event\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use HttpResponse;
    protected $courseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            $courses = $this->courseRepo->allCourses($search);
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function store(StoreCourseRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image')) {
                $photoPath = $request->file('image')->store('courses', 'public');
                $validatedData['image'] = asset('storage/' . $photoPath);
            }

            $course = $this->courseRepo->createCourse($validatedData);
            $createdCourse = $this->courseRepo->getById($course);

            return $this->success('success', CourseResource::make($createdCourse), 'Course Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function show(Course $course)
    {
        try {
            $course = $this->courseRepo->getById($course);
            return $this->success('success', CourseResource::make($course), 'Course Showed Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        try {
            $validatedData = $request->validated();
            
            if ($request->hasFile('image')) {
                if ($course->image && Storage::disk('public')->exists($course->image)) {
                    $dirPath = dirname($course->image);
                    Storage::disk('public')->deleteDirectory($dirPath);
                }

                $photoPath = $request->file('image')->store('courses', 'public');
                $validatedData['image'] = asset('storage/' . $photoPath);
            }

            $course = $this->courseRepo->updateCourse($validatedData, $course);
            $updatedCourse = $this->courseRepo->getById($course);

            return $this->success('success', CourseResource::make($updatedCourse), 'Course Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function destroy(Course $course)
    {
        try {
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                $dirPath = dirname($course->image);
                Storage::disk('public')->deleteDirectory($dirPath);
            }
            $course = $this->courseRepo->deleteById($course);
            return $this->success('success', null, 'Course Deleted Successfully', 204);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }
} 