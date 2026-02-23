<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Allow mass assignment (optional but helpful)
    protected $fillable = [
        'subject_id',
        'created_by',
        'title',
        'description',
        'due_at',
        'max_marks',
        'attachment_path',
    ];

    // If you want due_at auto cast to Carbon
    protected $casts = [
        'due_at' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function submissions()
{
    return $this->hasMany(Submission::class, 'assignment_id');
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}


}
