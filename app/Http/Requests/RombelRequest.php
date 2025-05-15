<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RombelRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'kelas' => 'required|uuid|exists:m_class,id',
      'nama' => 'required|string|max:255',
      'tahunAjaran' => 'required|uuid|exists:m_study_year,id',
      'waliKelas' => 'required|uuid|exists:m_teacher,id',
      'mataPelajaranJadwal' => 'nullable|uuid|exists:subject_schedules,id',
      'student' => 'nullable|uuid|exists:m_student,id',
    ];
  }

  public function validated($key = null, $default = null)
  {
    $validated = parent::validated();

    return [
      'name' => $validated['nama'],
      'class_id' => $validated['kelas'],
      'study_year_id' => $validated['tahunAjaran'],
      'teacher_id' => $validated['waliKelas'],
      'subject_schedule_id' => $validated['mataPelajaranJadwal'] ?? null,
      'student_id' => $validated['student'] ?? null,
    ];
  }
}
