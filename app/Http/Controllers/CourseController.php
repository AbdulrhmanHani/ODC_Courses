<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function addCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:courses,name',
            'content' => 'required|string|max:255',
            'skill_id' => 'required|exists:skills,id',
            'supplier_id' => 'required|exists:skills,id',
            'points' => 'required|numeric',
            'is_pre' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        } else {
            $c = Course::create([
                'name' => $request->name,
                'content' => $request->content,
                'skill_id' => $request->skill_id,
                'supplier_id' => $request->supplier_id,
                'points' => $request->points,
                'is_pre' => $request->is_pre,
            ]);

            return response()->json([
                'success' => "Course $c->name created successfully",
                $c,
            ]);
        }
    }

    public function deleteCourse($csId)
    {
        $course = Course::find($csId);
        if (!$course) {
            return response()->json([
                'error' => 'course not found',
            ]);
        } else {
            $course->delete();
            return response()->json([
                'success' => "Course $course->name deleted successfully",
            ]);
        }

    }

}
