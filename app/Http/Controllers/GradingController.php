<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class GradingController extends Controller
{
    private function ensureLecturer(): void
    {
        $u = auth()->user();
        if (!$u || ($u->user_type ?? '') !== 'lecturer') {
            abort(403, 'Lecturer only.');
        }
    }

    // ✅ THIS FIXES YOUR ERROR: /assignments/{assignment}/submissions
    public function index(Assignment $assignment)
    {
        $this->ensureLecturer();

        $submissions = Submission::with(['student', 'grade'])
            ->where('assignment_id', $assignment->id)
            ->latest('submitted_at')
            ->paginate(10);

        return view('grading.index', compact('assignment', 'submissions'));
    }

    // Show one submission grading page
    public function show(Assignment $assignment, Submission $submission)
    {
        $this->ensureLecturer();

        if ((int)$submission->assignment_id !== (int)$assignment->id) {
            abort(404);
        }

        $submission->load(['student', 'grade']);
        $assignment->load('subject');

        return view('grading.show', compact('assignment', 'submission'));
    }

    // Save grade (PRG pattern: POST then redirect)
    public function store(Request $request, Assignment $assignment, Submission $submission)
    {
        $this->ensureLecturer();

        if ((int)$submission->assignment_id !== (int)$assignment->id) {
            abort(404);
        }

        // max marks from assignment if exists, else fallback
        $max = 100;
        if (Schema::hasColumn('assignments', 'max_marks') && !is_null($assignment->max_marks)) {
            $max = (int)$assignment->max_marks;
        }

        $data = $request->validate([
            'marks'    => ['required', 'integer', 'min:0', 'max:'.$max],
            'feedback' => ['nullable', 'string'],
        ]);

        Grade::updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'graded_by' => auth()->id(),
                'marks'     => $data['marks'],
                'feedback'  => $data['feedback'] ?? null,
                'graded_at' => Carbon::now(),
            ]
        );

        if (Schema::hasColumn('submissions', 'status')) {
            $submission->status = 'graded';
        }
        $submission->save();

        // ✅ Proper way: redirect after POST
        return redirect()
            ->route('assignments.submissions', $assignment->id)
            ->with('success', 'Grade saved.');
    }
}
