<?php

namespace App\Http\Resources\Admin;

use App\Models\RombelModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RombelResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'class' => [
        'id' => $this->class->id,
        'name' => $this->class->name,
      ],
      'study_year' => [
        'id' => $this->studyYear->id,
        'semester' => $this->studyYear->semester,
        'year' => $this->studyYear->year,
      ],
      'teacher' => [
        'id' => $this->teacher->id,
        'name' => $this->teacher->name,
        'employee_number' => $this->teacher->employee_number,
      ],
      'student' => [
        'id' => $this->student->id,
        'name' => $this->student->name,
        'student_number' => $this->student->student_number,
        'status' => $this->student->status,
      ],
      'subject_schedules' => $this->subjectSchedules ? $this->subjectSchedules->map(function ($schedule) {
        return [
          'id' => $schedule->id,
          'subject_name' => $schedule->subject->name ?? null,
        ];
      }) : [],
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
  }
}
