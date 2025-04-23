<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\ClassResource;
use App\Http\Resources\Admin\SubjectResource;
use App\Http\Resources\Admin\SubjectHourResource;
use App\Http\Resources\Admin\TeacherResource;

class SubjectScheduleResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'day' => $this->day,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'class' => new ClassResource($this->whenLoaded('class')),
      'subject' => new SubjectResource($this->whenLoaded('subject')),
      'teacher' => new TeacherResource($this->whenLoaded('teacher')),
      'subject_hour' => new SubjectHourResource($this->whenLoaded('subjectHour')),
    ];
  }
}
