<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'graded_by',
        'marks',
        'feedback',
        'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
