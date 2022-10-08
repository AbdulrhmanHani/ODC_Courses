<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Skill;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function getStarted(Request $request)
    {
        if ($request->header('token')) {
            $student = Student::where('token', '=', $request->header('token'))->first();
            if ($student) {
                return response()->json([
                    'msg' => "Welcome Student $student->name",
                    'your skill' => $student->Skill['name'],
                    'your points' => $student->points,
                    'next skill' => DB::table('skills')
                        ->where('id', '=', $student->Skill->id + 1)->first()->name ?? $student->Skill->name . " is the latest Skill",
                    'Courses Opened' => CourseResource::collection($student->Skill->Courses),

                ]);
            } else {
                return response()->json([
                    'error' => 'token is not vaild',
                ]);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50|unique:students,name',
                'email' => 'required|email|max:50|unique:students,email',
                'address' => 'required|string|max:255',
                'phone' => 'required|numeric|unique:students,phone',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            } else {
                $skillId = Skill::where('name', '=', 'new')->first();
                Student::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'points' => 0,
                    'skill_id' => $skillId->id,
                    'token' => Str::random(64),
                ]);
                return response()->json([
                    'success' => "Student $request->name created successfully",
                ]);
            }
        }
    }

    public function changeStudentSkillStatus(Request $request, $cName)
    {
        if ($request->header('token')) {
            $student = Student::where('token', '=', $request->header('token'))->first();
            if ($student) {
                $studentCoursesInCname = $student->Skill->Courses->where('name', '=', $cName)->first();
                if ($studentCoursesInCname) {
                    if ($student->skill_status !== 'opened') {
                        $student->update([
                            'skill_status' => 'opened',
                        ]);
                        return response()->json([
                            'success' => 'you completed this course wait until admins approve your submittion',
                            'check' => 'check your email in next 24 hours',
                            'next skill' => DB::table('skills')
                                ->where('id', '=', $student->Skill->id + 1)->first()->name ?? $student->Skill->name . " is the latest Skill",
                        ]);
                    } else {
                        return response()->json([
                            'success' => 'you already completed this course wait until admins approve your submittion',
                            'next skill' => DB::table('skills')
                                ->where('id', '=', $student->Skill->id + 1)->first()->name ?? $student->Skill->name . " is the latest Skill",
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'error' => 'wrong access token',
                ]);
            }
        } else {
            return response()->json([
                'error' => 'you are not allowed',
            ]);
        }
    }
}
