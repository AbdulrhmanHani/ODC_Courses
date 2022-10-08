<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function addSkill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:skills,name|string|max:50',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        } else {
            Skill::create([
                'name' => $request->name,
            ]);
            return response()->json([
                'success' => "Skill $request->name created successfully",
            ]);
        }
    }

    public function deleteSkill($skillId)
    {
        $skill = Skill::find($skillId);
        if (!$skill) {
            return response()->json([
                'error' => "skill not found"
            ], 404);
        } else {
            $skill->delete();
            return response()->json([
                'success' => "Skill $skill->name deleted successfully",
            ]);
        }
    }
}
