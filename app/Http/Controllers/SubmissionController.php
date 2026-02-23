<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    private function ensureStudent(): void
    {
        $u = auth()->user();
        if (!$u || ($u->user_type ?? '') !== 'student') {
            abort(403, 'Student only.');
        }
    }

    private function ensureOwner(Submission $submission): void
    {
        $this->ensureStudent();

        if ((int)$submission->student_id !== (int)auth()->id()) {
            abort(403, 'Not allowed.');
        }
    }

    /**
     * A submission is LOCKED once it is graded.
     * We treat it as graded if:
     * - status == 'graded' OR
     * - a related Grade record exists.
     */
    private function isLocked(Submission $submission): bool
    {
        if (($submission->status ?? '') === 'graded') {
            return true;
        }

        if ($submission->relationLoaded('grade')) {
            return (bool) $submission->grade;
        }

        return $submission->grade()->exists();
    }

    public function create(Assignment $assignment)
    {
        $this->ensureStudent();

        // If already submitted, do NOT allow resubmit/create again.
        $existing = Submission::with('grade')
            ->where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing) {
            if ($this->isLocked($existing)) {
                return redirect()
                    ->route('submissions.my')
                    ->with('error', 'This submission has been graded and is locked. You can only download it.');
            }

            return redirect()
                ->route('submissions.edit', $existing->id)
                ->with('error', 'You already submitted. Please edit your submission (not resubmit).');
        }

        return view('submissions.create', compact('assignment'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $this->ensureStudent();

        $studentId = (int) auth()->id();

        // Block duplicate submission
        $existing = Submission::with('grade')
            ->where('assignment_id', $assignment->id)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) {
            if ($this->isLocked($existing)) {
                return redirect()
                    ->route('submissions.my')
                    ->with('error', 'This submission has been graded and is locked. You cannot resubmit.');
            }

            return redirect()
                ->route('submissions.edit', $existing->id)
                ->with('error', 'Submission already exists. Please use Edit Submission.');
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $isLate = false;
        if (!empty($assignment->due_at)) {
            $isLate = now()->gt($assignment->due_at);
        }

        // Save into: storage/app/private/submissions/assignment_{id}/student_{id}.pdf
        $dir  = "private/submissions/assignment_{$assignment->id}";
        $name = "student_{$studentId}.pdf";
        $path = $request->file('file')->storeAs($dir, $name, 'local');

        $submission = new Submission();
        $submission->assignment_id = $assignment->id;
        $submission->student_id    = $studentId;
        $submission->file_path     = $path;
        $submission->submitted_at  = now();
        $submission->is_late       = $isLate;
        $submission->status        = 'submitted';
        $submission->save();

        return redirect()
            ->route('submissions.my')
            ->with('success', 'Submission uploaded successfully.');
    }

    public function my()
    {
        $this->ensureStudent();

        $submissions = Submission::with(['assignment.subject', 'grade'])
            ->where('student_id', auth()->id())
            ->latest('submitted_at')
            ->paginate(10);

        return view('submissions.my', compact('submissions'));
    }

    public function edit(Submission $submission)
    {
        $this->ensureOwner($submission);

        $submission->load(['assignment.subject', 'grade']);
        $locked = $this->isLocked($submission);

        return view('submissions.edit', compact('submission', 'locked'));
    }

    public function update(Request $request, Submission $submission)
    {
        $this->ensureOwner($submission);

        if ($this->isLocked($submission)) {
            return redirect()
                ->route('submissions.edit', $submission->id)
                ->with('error', 'This submission has been graded and is locked. You cannot edit it.');
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $assignment = $submission->assignment ?? Assignment::findOrFail($submission->assignment_id);

        $isLate = false;
        if (!empty($assignment->due_at)) {
            $isLate = now()->gt($assignment->due_at);
        }

        // delete old file
        if (!empty($submission->file_path) && Storage::disk('local')->exists($submission->file_path)) {
            Storage::disk('local')->delete($submission->file_path);
        }

        $studentId = (int) auth()->id();
        $dir  = "private/submissions/assignment_{$assignment->id}";
        $name = "student_{$studentId}.pdf";
        $path = $request->file('file')->storeAs($dir, $name, 'local');

        $submission->file_path    = $path;
        $submission->submitted_at = now();
        $submission->is_late      = $isLate;
        $submission->status       = 'updated';
        $submission->save();

        return redirect()
            ->route('submissions.my')
            ->with('success', 'Submission updated successfully.');
    }

    public function destroy(Submission $submission)
    {
        $this->ensureOwner($submission);

        if ($this->isLocked($submission)) {
            return redirect()
                ->route('submissions.my')
                ->with('error', 'This submission has been graded and is locked. You cannot delete it.');
        }

        if (!empty($submission->file_path) && Storage::disk('local')->exists($submission->file_path)) {
            Storage::disk('local')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()
            ->route('submissions.my')
            ->with('success', 'Submission deleted successfully.');
    }

    public function download(Submission $submission)
    {
        $u = auth()->user();
        if (!$u) abort(403);

        // Student can download own; lecturer can download all
        if (($u->user_type ?? '') !== 'lecturer' && (int)$submission->student_id !== (int)$u->id) {
            abort(403, 'Not allowed.');
        }

        $path = $submission->file_path;

        if (empty($path) || !Storage::disk('local')->exists($path)) {
            return back()->with('error', 'Submission file not found.');
        }

        $stamp = optional($submission->updated_at)->format('YmdHis') ?? now()->format('YmdHis');
        $downloadName = "submission_{$submission->id}_{$stamp}.pdf";

        return Storage::disk('local')->download($path, $downloadName, [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
}
