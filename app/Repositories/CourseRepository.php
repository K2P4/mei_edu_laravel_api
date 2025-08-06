<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    public function allCourses($search = null)
    {
        $query = Course::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        return $query->paginate(30);
    }

    public function createCourse($data)
    {
        return Course::create($data);
    }

    public function getById(Course $course)
    {
        return $course;
    }

    public function updateCourse($data, $course)
    {
        $course->update($data);
        return $course;
    }

    public function deleteById(Course $course)
    {
        $course->delete();
        return $course;
    }
} 