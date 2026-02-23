@extends('layouts.app')

@section('title', 'Grade Submission')

@section('content')
<style>
    .wrap { max-width: 900px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:800; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:12px; }
    .badge{ display:inline-block; padding:4px 10px; border-radius:999px; background:#f2efdf; border:1px solid #e3ddc9; font-size:12px; font-weight:800; color:#6a5226; }
    label { font-weight:700; font-size:13px; color:#333; display:block; margin-bottom:6px; }
    input, textarea {
        width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none;
    }
    textarea { min-height:120px; resize:vertical; }
    .row { margin-bottom:12px; }
    .btn { padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:800; text-decoration:none; display:inline-flex; gap:8px; }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
    .flash{ padding:12px 14px; border-radius:12px; margin-bottom:12px; background:#eaf6ea; border:1px solid #bfe3bf; color:#1f5f1f; font-weight:700; }
    .error { color:#b00020; font-size:13px; margin-top:6px; }
</style>

<div class="wrap">
    @if(session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="title">Grade Submission</div>
        <div class="muted">
            <div><strong>Assignment:</strong> {{ $assignment->title }}</div>
            <div><strong>Student:</strong> {{ $submission->student->name ?? 'Student' }} ({{ $submission->student->username ?? '' }})</div>
            <div><strong>Submitted:</strong> {{ optional($submission->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                @if($submission->is_late) <span class="badge">Late</span> @endif
            </div>
            <div><strong>Status:</strong> <span class="badge">{{ ucfirst($submission->status) }}</span></div>
        </div>

        <div class="actions" style="margin-bottom:14px;">
            <a class="btn btn-light" href="{{ route('assignments.submissions', $assignment->id) }}">Back</a>
            <a class="btn btn-light" href="{{ route('submissions.download', $submission->id) }}">Download PDF</a>
        </div>

        <form method="POST" action="{{ route('grading.store', [$assignment->id, $submission->id]) }}">
            @csrf

            <div class="row">
                <label>Marks (0 - {{ $assignment->max_marks ?? 100 }}) *</label>
                <input type="number" name="marks" value="{{ old('marks', optional($submission->grade)->marks) }}" min="0" max="{{ $assignment->max_marks ?? 100 }}">
                @error('marks') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <label>Feedback (optional)</label>
                <textarea name="feedback">{{ old('feedback', optional($submission->grade)->feedback) }}</textarea>
                @error('feedback') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Save Grade</button>
            </div>
        </form>
    </div>
</div>
@endsection
