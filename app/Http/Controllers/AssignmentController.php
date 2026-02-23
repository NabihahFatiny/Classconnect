<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function isLecturer(): bool
    {
        return auth()->check() && (auth()->user()->user_type ?? '') === 'lecturer';
    }

    private function ensureLecturer(): void
    {
        if (!$this->isLecturer()) {
            abort(403, 'Lecturer only.');
        }
    }

    private function subjectsList()
    {
        // If Subject model/table exists, return subjects; otherwise empty collection
        try {
            if (class_exists(Subject::class)) {
                return Subject::orderBy('id', 'desc')->get();
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return collect();
    }

    public function index(Request $request)
{
    $user = auth()->user();

    $query = \App\Models\Assignment::with(['subject']);

    // Student: load only THEIR submission per assignment (and grade if any)
    if ($user && ($user->user_type ?? '') === 'student') {
        $query->with(['submissions' => function ($q) use ($user) {
            $q->where('student_id', $user->id)->with('grade');
        }]);
    }

    // Optional search
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($w) use ($q) {
            $w->where('title', 'like', "%{$q}%")
              ->orWhere('description', 'like', "%{$q}%");
        });
    }

    $assignments = $query->latest()->paginate(10);

    return view('assignments.index', compact('assignments'));
}


    public function create()
    {
        $this->ensureLecturer();

        $subjects = $this->subjectsList();

        return view('assignments.create', compact('subjects'));
    }

    public function store(Request $request)
{
    $this->ensureLecturer();

    $data = $request->validate(
        [
            'subject_id'  => ['nullable', 'integer'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at'      => ['nullable', 'string'], // we parse manually
            'max_marks'   => ['nullable', 'integer', 'min:0', 'max:1000'],
            'attachment'  => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ],
        [
            'attachment.mimes' => 'Only PDF files are allowed.',
        ]
    );

    // Convert "2025-12-19T02:36" -> "2025-12-19 02:36:00"
    $dueAt = null;
    if (!empty($data['due_at'])) {
        $dueAt = str_replace('T', ' ', $data['due_at']);
        if (strlen($dueAt) === 16) $dueAt .= ':00';
    }

    $a = new \App\Models\Assignment();
    $a->subject_id  = $data['subject_id'] ?? null;
    $a->created_by  = auth()->id();
    $a->title       = $data['title'];
    $a->description = $data['description'] ?? null;
    $a->due_at      = $dueAt;
    $a->max_marks   = $data['max_marks'] ?? null;

    if ($request->hasFile('attachment')) {
        $a->attachment_path = $request->file('attachment')->store('assignments', 'local');
    }

    $a->save();

    return redirect()->route('assignments.index')->with('success', 'Assignment created.');
}

    public function show(Assignment $assignment)
    {
        // both student & lecturer can view
        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        $this->ensureLecturer();

        // Optional: ensure only owner lecturer can edit
        if (Schema::hasColumn('assignments', 'created_by') && (int)$assignment->created_by !== (int)auth()->id()) {
            abort(403, 'You can only edit your own assignments.');
        }

        $subjects = $this->subjectsList();

        return view('assignments.edit', compact('assignment', 'subjects'));
    }

    public function update(Request $request, Assignment $assignment)
{
    $this->ensureLecturer();

    if (Schema::hasColumn('assignments', 'created_by') && (int)$assignment->created_by !== (int)auth()->id()) {
        abort(403, 'You can only update your own assignments.');
    }

    $rules = [];

    if (Schema::hasColumn('assignments', 'subject_id')) {
        $rules['subject_id'] = ['nullable', 'integer'];
    }
    if (Schema::hasColumn('assignments', 'title')) {
        $rules['title'] = ['required', 'string', 'max:255'];
    }
    if (Schema::hasColumn('assignments', 'description')) {
        $rules['description'] = ['nullable', 'string'];
    }
    if (Schema::hasColumn('assignments', 'due_at')) {
        $rules['due_at'] = ['nullable', 'date'];
    }
    if (Schema::hasColumn('assignments', 'max_marks')) {
        $rules['max_marks'] = ['nullable', 'integer', 'min:0', 'max:1000'];
    }
    if (Schema::hasColumn('assignments', 'attachment_path')) {
        // ✅ PDF only
        $rules['attachment'] = ['nullable', 'file', 'mimes:pdf', 'max:10240']; // 10MB
    }

    $messages = [
        'attachment.mimes' => 'Only PDF files are allowed.',
    ];

    $data = $request->validate($rules, $messages);

    if (Schema::hasColumn('assignments', 'subject_id')) {
        $assignment->subject_id = $data['subject_id'] ?? null;
    }
    if (Schema::hasColumn('assignments', 'title')) {
        $assignment->title = $data['title'];
    }
    if (Schema::hasColumn('assignments', 'description')) {
        $assignment->description = $data['description'] ?? null;
    }
    if (Schema::hasColumn('assignments', 'due_at')) {
        $assignment->due_at = $data['due_at'] ?? null;
    }
    if (Schema::hasColumn('assignments', 'max_marks')) {
        $assignment->max_marks = $data['max_marks'] ?? null;
    }

    if (Schema::hasColumn('assignments', 'attachment_path') && $request->hasFile('attachment')) {
        if (!empty($assignment->attachment_path)) {
            Storage::disk('local')->delete($assignment->attachment_path);
        }
        $assignment->attachment_path = $request->file('attachment')->store('assignments', 'local');
    }

    $assignment->save();

    return redirect()->route('assignments.index')->with('success', 'Assignment updated.');
}


    public function destroy(Assignment $assignment)
    {
        $this->ensureLecturer();

        if (Schema::hasColumn('assignments', 'created_by') && (int)$assignment->created_by !== (int)auth()->id()) {
            abort(403, 'You can only delete your own assignments.');
        }

        if (Schema::hasColumn('assignments', 'attachment_path') && !empty($assignment->attachment_path)) {
            Storage::disk('local')->delete($assignment->attachment_path);
        }

        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Assignment deleted.');
    }

    public function download(Assignment $assignment)
{
    // Optional: restrict who can download
    if (!auth()->check()) abort(403);

    $path = $assignment->attachment_path;

    if (empty($path)) {
        return back()->with('error', 'No attachment found for this assignment.');
    }

    // IMPORTANT: check exists before downloading
    if (!Storage::disk('local')->exists($path)) {
        return back()->with('error', 'Attachment file not found. Please re-upload the attachment.');
    }

    $filename = 'assignment_'.$assignment->id.'_attachment.pdf';

    // This avoids Windows path issues
    return Storage::disk('local')->download($path, $filename);
}
}
