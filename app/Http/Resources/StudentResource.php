<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->skill_status == 'opened') {
            $skill = ['approve_student_upgrade_request' => url("admin/approve/upgrade-skill/$this->id"),
                'cancel_student_upgrade_request' => url("admin/cancel/upgrade-skill/$this->id")];
        } else {
            $skill = 'waiting student to take a skill courses';
        }
        return [
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'skill' => $this->Skill()->first('name'),
            'points' => $this->points,
            'student_skill_status' => $this->skill_status,
            'delete_student' => url("/api/admin/delete-student/$this->id"),
            'approve_or_cancel' => $skill,
        ];
    }
}
