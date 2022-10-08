<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResourse;
use App\Http\Resources\SkillResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\SupplierResource;
use App\Models\Course;
use App\Models\Key;
use App\Models\Profit;
use App\Models\Skill;
use App\Models\Student;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    # REDIRECT REQUEST #
    public function GET()
    {
        return response()->json([
            'error' => 'only post request allowed',
        ]);
    }
    public function POST()
    {
        return response()->json([
            'error' => 'only get request allowed',
        ]);
    }
    # /REDIRECT REQUEST #

    public function index(Request $request)
    {
        $data['students'] = Student::all()->count();
        $data['courses_count'] = Course::all()->count();
        $data['courses'] = Course::all('name', 'content', 'is_pre', 'points');
        $data['skills_count'] = Skill::all()->count();
        $data['skills'] = Skill::all('name');
        $data['suppliers_count'] = Supplier::all()->count();
        $data['suppliers'] = SupplierResource::collection(Supplier::all());
        $data['total_profits'] = Profit::all()->sum('profit') . ' $';
        $data['profits'] = Profit::all(['course_id', 'student_id', 'profit']);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'data' => encrypt($data),
                ]);
            }
        } else {
            return response()->json([
                'data' => encrypt($data),
            ]);
        }

    }

    public function students(Request $request)
    {
        $students = Student::paginate(5, ['id', 'name', 'email',
            'address', 'phone', 'skill_id', 'skill_status', 'points']);

        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'data' => $students,
                ]);
            } else {
                return response()->json([
                    'students' => encrypt($students),
                ]);
            }
        } else {
            return response()->json([
                'students' => encrypt($students),
            ]);
        }
    }

    public function student(Request $request, $stId)
    {
        $student = Student::find($stId);
        if (!$student) {
            return response()->json([
                'error' => 'student not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'student' => new StudentResource($student),
                    ]);
                } else {
                    return response()->json([
                        'student' => encrypt(new StudentResource($student)),
                    ]);
                }
            } else {
                return response()->json([
                    'student' => encrypt(new StudentResource($student)),
                ]);
            }
        }
    }

    public function studentSearch(Request $request)
    {
        $key = $request->get('key');
        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        } else {
            $students = Student::where('name', 'LIKE', "%$key%")
                ->orWhere('email', 'LIKE', "%$key%")
                ->orWhere('address', 'LIKE', "%$key%")
                ->orWhere('phone', 'LIKE', "%$key%")
                ->orWhere('gender', 'LIKE', "$key")
                ->orWhere('skill_status', 'LIKE', "$key")
                ->get();
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'students' => StudentResource::collection($students),
                    ]);
                } else {
                    return response()->json([
                        'students' => encrypt(StudentResource::collection($students)),
                    ]);
                }
            } else {
                return response()->json([
                    'students' => encrypt(StudentResource::collection($students)),
                ]);
            }
        }
    }

    public function topStudents(Request $request, $num)
    {
        $students = Student::orderBy('points', 'DESC')->take($num)->get();
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'students' => $students,
                ]);
            } else {
                return response()->json([
                    'students' => encrypt($students),
                ]);
            }
        } else {
            return response()->json([
                'students' => encrypt($students),
            ]);

        }

    }

    public function courses(Request $request)
    {
        $courses = Course::paginate(5);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'courses' => $courses,
                ]);
            } else {
                return response()->json([
                    'courses' => encrypt($courses),
                ]);
            }
        } else {
            return response()->json([
                'courses' => encrypt($courses),
            ]);
        }

    }

    public function course(Request $request, $cId)
    {
        $course = Course::find($cId);
        if (!$course) {
            return response()->json([
                'error' => 'course not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'course' => $course,
                        'course_supplier' => $course->Supplier,
                        'course_skills' => $course->Skill,
                        'delete_course' => url("api/admin/delete-course/$course->id"),
                    ]);
                } else {
                    return response()->json([
                        'course' => encrypt($course),
                        'course_supplier' => encrypt($course->Supplier),
                        'course_skills' => encrypt($course->Skill),
                        'delete_course' => encrypt(url("api/admin/delete-course/$course->id")),
                    ]);
                }
            } else {
                return response()->json([
                    'course' => encrypt($course),
                    'course_supplier' => encrypt($course->Supplier),
                    'course_skills' => encrypt($course->Skill),
                    'delete_course' => encrypt(url("api/admin/delete-course/$course->id")),
                ]);
            }

        }
    }

    public function deleteCourse(Request $request, $cId)
    {
        $course = Course::find($cId);

        if (!$course) {
            return response()->json([
                'error' => 'course not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    $course->delete();
                    return response()->json([
                        'success' => "course $course->name deleted successfully",
                    ]);
                } else {
                    return response()->json([
                        'success' => encrypt("can not delete course $course->name "),
                    ]);
                }
            } else {
                return response()->json([
                    'success' => encrypt("can not delete course $course->name "),
                ]);
            }

        }

    }

    public function skills(Request $request)
    {
        $skills = Skill::paginate(5);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'skills' => $skills,
                ]);
            } else {
                return response()->json([
                    'skills' => encrypt($skills),
                ]);
            }
        } else {
            return response()->json([
                'skills' => encrypt($skills),
            ]);

        }
    }

    public function skill(Request $request, $skillId)
    {
        $skill = Skill::find($skillId);
        if (!$skill) {
            return response()->json([
                'error' => 'skill not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'skill' => new SkillResource($skill),
                    ]);
                } else {
                    return response()->json([
                        'skill' => encrypt(new SkillResource($skill)),
                    ]);
                }
            } else {
                return response()->json([
                    'skill' => encrypt(new SkillResource($skill)),
                ]);
            }
        }
    }

    public function suppliers(Request $request)
    {
        $suppliers = Supplier::paginate(5);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'suppliers' => $suppliers,
                ]);
            } else {
                return response()->json([
                    'suppliers' => encrypt($suppliers),
                ]);
            }
        } else {
            return response()->json([
                'suppliers' => encrypt($suppliers),
            ]);
        }
    }

    public function supplier(Request $request, $suppId)
    {
        $supplier = Supplier::find($suppId);
        if (!$supplier) {
            return response()->json([
                'error' => 'supplier not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'supplier' => new SupplierResource($supplier),
                    ]);
                } else {
                    return response()->json([
                        'supplier' => encrypt(new SupplierResource($supplier)),
                    ]);
                }
            } else {
                return response()->json([
                    'supplier' => encrypt(new SupplierResource($supplier)),
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        } else {
            $admin = User::where('email', '=', $request->email)->first();
            if ($admin) {
                $password = $request->password;
                if (Hash::check($password, $admin->password)) {
                    $access_token = Str::random(64);
                    $admin->update([
                        'access_token' => $access_token,
                    ]);
                    return response()->json([
                        'success' => "admin $admin->name logged in successfully",
                        'token' => $access_token,
                        'key' => DB::table('keys')->first()->key,
                    ]);
                } else {
                    return response()->json([
                        'errors' => 'email or password is incorrect',
                    ]);
                }
            } else {
                return response()->json([
                    'error' => 'email or password in incorrect',
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        if ($request->header('access_token')) {
            $admin = User::where('access_token', '=', $request->header('access_token'))->first();
            if ($admin) {
                $admin->update([
                    'access_token' => null,
                ]);
                return response()->json([
                    'success' => "admin $admin->name logged out successfully",
                ]);
            } else {
                return response()->json([
                    'error' => 'wrong access token',
                ]);
            }
        } else {
            return response()->json([
                'error' => 'no access token',
            ]);
        }
    }

    public function deleteStudent(Request $request, $stId)
    {
        $student = Student::find($stId);
        if (!$student) {
            return response()->json([
                'error' => 'Student not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    $student->delete();
                    return response()->json([
                        'success' => "Student $student->name deleted successfully",
                    ]);
                } else {
                    return response()->json([
                        'error' => "not valid key",
                    ]);
                }
            } else {
                return response()->json([
                    'error' => "not valid key",
                ]);
            }
        }
    }

    public function addAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    $isCorrect2 = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => 'admin',
                        'password' => bcrypt($request->password),
                    ]);
                    if ($isCorrect2) {
                        return response()->json([
                            'success' => "admin $request->name created successfully",
                        ]);
                    } else {
                        return response()->json([
                            'error' => "can not create admin $request->name",
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => "not valid key",
                    ]);
                }
            } else {
                return response()->json([
                    'error' => "not valid key",
                ]);
            }

        }
    }

    public function admins(Request $request)
    {
        $admins = User::where('role', '=', 'admin')->paginate(5, ['name', 'email']);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'admin' => $admins,
                ]);
            } else {
                return response()->json([
                    'admin' => encrypt($admins),
                ]);
            }
        } else {
            return response()->json([
                'admin' => encrypt($admins),
            ]);
        }

    }

    public function admin(Request $request, $adId)
    {
        $admin = User::find($adId);
        if (!$admin) {
            return response()->json([
                'error' => 'admin not found',
            ]);
        } else {
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'admin' => new AdminResourse($admin),
                    ]);
                } else {
                    return response()->json([
                        'admin' => encrypt(new AdminResourse($admin)),
                    ]);
                }
            } else {
                return response()->json([
                    'admin' => encrypt(new AdminResourse($admin)),
                ]);
            }
        }
    }

    public function deleteAdmin($adId)
    {
        $admin = User::find($adId);
        if (!$admin) {
            return response()->json([
                'error' => 'admin not found',
            ]);
        } else {
            if ($admin->role == 'super_admin') {
                return response()->json([
                    'error' => 'can not delete super admin',
                ]);
            } else {
                $admin->delete();
                return response()->json([
                    'success' => "admin $admin->name deleted successfully",
                ]);
            }
        }
    }

    public function approveStudentUpgradeSkill(Request $request, $stId)
    {
        $student = Student::find($stId);
        if (!$student) {
            return response()->json([
                'error' => 'wrong student id',
            ]);
        } else {
            $token = Str::random(64);
            $studentNextSkill = DB::table('skills')
                ->where('id', '=', $student->Skill->id + 1)
                ->first() ?? $student->Skill->name . 'Is the latest skill';
            $student->update([
                'skill_id' => $studentNextSkill->id ?? $student->Skill->id,
                'skill_status' => 'not_opened',
                'points' => $student->points + $student->Skill->Courses->first()->points ?? 0,
                'token' => $token,

            ]);
            Profit::create([
                'course_id' => $student->Skill->Courses->first()->id,
                'student_id' => $student->id,
                'profit' => ($student->Skill->Courses->first()->Supplier->amount * 0.01),
            ]);

            $supplier = $student->Skill->Courses->first()->Supplier;
            $supplier->update([
                'recived' => ($student->Skill->Courses->first()->Supplier->amount * 0.01),
            ]);
            if ($request->header('key')) {
                $isCorrect = Key::where('key', '=', $request->header('key'))->first();
                if ($isCorrect) {
                    return response()->json([
                        'success' => "student $student->name Successfully has skill $studentNextSkill->name",
                        'student_token' => $token,
                        'profits' => ($student->Skill->Courses->first()->Supplier->amount * 0.01),
                    ]);
                } else {
                    return response()->json([
                        'success' => encrypt("student $student->name Successfully has skill $studentNextSkill->name"),
                        'student_token' => encrypt($token),
                        'profits' => encrypt(($student->Skill->Courses->first()->Supplier->amount * 0.01)),
                    ]);
                }
            }

        }
    }

    public function cancelStudentUpgradeSkill(Request $request, $stId)
    {
        $student = Student::find($stId);
        $student->update([
            'skill_status' => 'not_opened',
        ]);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    'msg' => 'user request to upgrade skill is rejected',
                ]);
            } else {
                return response()->json([
                    'msg' => encrypt('user request to upgrade skill is rejected'),
                ]);
            }
        } else {
            return response()->json([
                'msg' => encrypt('user request to upgrade skill is rejected'),
            ]);
        }
    }

    public function profits(Request $request)
    {
        $profits = Profit::paginate(5, ['course_id', 'student_id', 'profit']);
        if ($request->header('key')) {
            $isCorrect = Key::where('key', '=', $request->header('key'))->first();
            if ($isCorrect) {
                return response()->json([
                    "Total_Profits" => DB::table('profits')->sum('profit') . '$',
                    'profits' => $profits,
                ]);
            } else {
                return response()->json([
                    "Total_Profits" => encrypt(DB::table('profits')->sum('profit') . '$'),
                    'profits' => encrypt($profits),
                ]);
            }
        } else {
            return response()->json([
                "Total_Profits" => encrypt(DB::table('profits')->sum('profit') . '$'),
                'profits' => encrypt($profits),
            ]);
        }
    }
}
# This is the end for now
