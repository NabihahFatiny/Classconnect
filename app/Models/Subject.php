<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
