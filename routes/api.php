<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

#----------------------------------------------------------------------------------------------------------------
# Tests #
#----------------------------------------------------------------------------------------------------------------

#Admin Auth#
# Login
Route::post('admin/login', [AdminController::class, 'login']);
Route::get('admin/login', [AdminController::class, 'GET']);
# Logout
Route::get('admin/logout', [AdminController::class, 'logout']);
Route::post('admin/logout', [AdminController::class, 'POST']);

#/Admin Auth#

#Super Admin Section#
Route::middleware('super_admin')->group(function () {
    #----------------------------------------------------------------------------------------------------------------
    # add admin
    Route::post('admin/add-admin', [AdminController::class, 'addAdmin']);
    Route::get('admin/add-admin', [AdminController::class, 'GET']);
    # delete admin
    Route::get('/admin/delete-admin/{adId}', [AdminController::class, 'deleteAdmin']);
    Route::post('admin/delete-admin/{adId}', [AdminController::class, 'POST']);

    # delete student
    Route::get('/admin/delete-student/{stId}', [AdminController::class, 'deleteStudent']);
    Route::post('admin/delete-student/{stId}', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # admins
    route::get('admin/admins', [AdminController::class, 'admins']);
    Route::post('admin/admins', [AdminController::class, 'POST']);
    # show admin
    route::get('admin/admin/{adId}', [AdminController::class, 'admin']);
    Route::post('admin/admin/{adId}', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # profits
    route::get('admin/profits', [AdminController::class, 'profits']);
    Route::post('admin/profits', [AdminController::class, 'POST']);
});
#Super Admin Section#

#Admin Section#
Route::middleware('admin')->group(function () {
    #----------------------------------------------------------------------------------------------------------------
    # Admin Dashboard #
    # index
    route::get('admin/dashboard', [AdminController::class, 'index']);
    Route::post('admin/dashboard', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # students
    route::get('admin/students', [AdminController::class, 'students']);
    Route::post('admin/students', [AdminController::class, 'POST']);
    # student
    route::get('admin/student/{stId}', [AdminController::class, 'student']);
    Route::post('admin/student/{stId}', [AdminController::class, 'POST']);
    # student search
    route::get('admin/student-search', [AdminController::class, 'studentSearch']);
    Route::post('admin/student-search', [AdminController::class, 'POST']);
    # student top num
    route::get('admin/student/top/{num}', [AdminController::class, 'topStudents']);
    Route::post('admin/student/top/{num}', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # courses
    route::get('admin/courses', [AdminController::class, 'courses']);
    Route::post('admin/courses', [AdminController::class, 'POST']);
    # add course
    Route::post('admin/add-course', [CourseController::class, 'addCourse']);
    Route::get('admin/add-course', [AdminController::class, 'GET']);
    # course
    route::get('admin/course/{cId}', [AdminController::class, 'course']);
    Route::post('admin/course/{cId}', [AdminController::class, 'POST']);
    # delete course
    route::get('admin/delete-course/{cId}', [AdminController::class, 'deleteCourse']);
    Route::post('admin/delete-course/{cId}', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # skills
    route::get('admin/skills', [AdminController::class, 'skills']);
    Route::post('admin/skills', [AdminController::class, 'POST']);
    # skill
    route::get('admin/skill/{skillId}', [AdminController::class, 'skill']);
    Route::post('admin/skill/{skillId}', [AdminController::class, 'POST']);
    # add skill
    Route::post('admin/add-skill', [SkillController::class, 'addSkill']);
    Route::get('admin/add-skill', [AdminController::class, 'GET']);
    # delete skill
    Route::get('admin/delete-skill/{skillId}', [SkillController::class, 'deleteSkill']);
    Route::post('admin/delete-skill/{skillId}', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # suppliers
    route::get('admin/suppliers', [AdminController::class, 'suppliers']);
    Route::post('admin/suppliers', [AdminController::class, 'POST']);
    # supplier
    route::get('admin/supplier/{suppId}', [AdminController::class, 'supplier']);
    Route::post('admin/supplier/{suppId}', [AdminController::class, 'POST']);
    # add supplier
    Route::post('admin/add-supplier', [SupplierController::class, 'addSupplier']);
    Route::get('admin/add-supplier', [AdminController::class, 'GET']);
    # delete supplier
    Route::get('admin/delete-supplier/{suppId}', [SupplierController::class, 'deleteSupplier']);
    Route::post('admin/delete-supplier/{suppId}', [AdminController::class, 'POST']);
    #-------------------------------------------------------------------------------
    # approve student upgrade skill
    Route::get('admin/approve/upgrade-skill/{stId}', [AdminController::class, 'approveStudentUpgradeSkill']);
    Route::post('admin/approve/upgrade-skill/{stId}', [AdminController::class, 'POST']);
    # cancel student upgrade skill
    Route::get('admin/cancel/upgrade-skill/{stId}', [AdminController::class, 'cancelStudentUpgradeSkill']);
    Route::post('admin/cancel/upgrade-skill/{stId}', [AdminController::class, 'POST']);
    # /Admin Dashboard #
});
#/Admin Section#

#Student Section#
# add student || handle student courses
Route::post('/', [StudentController::class, 'getStarted']);
Route::get('/', [AdminController::class, 'GET']);
# check if student opened course
Route::get('course/{cName}', [StudentController::class, 'changeStudentSkillStatus']);
Route::post('course/{cName}', [AdminController::class, 'POST']);
#/Student Section#
