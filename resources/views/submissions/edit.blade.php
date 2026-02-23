@extends('layouts.app')

@section('title', 'Edit Submission')

@section('content')
<style>
    .wrap { max-width: 850px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:800; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:16px; }
    .row { margin-bottom:12px; }
    label { font-weight:700; font-size:13px; color:#333; display:block; margin-bottom:6px; }
    input { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none; }
    .btn { padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:800; text-decoration:none; display:inline-flex; gap:8px; align-items:center; }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .btn-danger { background:#ffe9e9; color:#b00020; }
    .btn-danger:hover { background:#ffdcdc; }
    .error { color:#b00020; font-size:13px; margin-top:6px; }
    .badge { display:inline-block; padding:4px 10px; border-radius:999px; background:#f2efdf; border:1px solid #e3ddc9; font-size:12px; font-weight:800; color:#6a5226; }
    .badge-lock{ background:#eef2ff; border-color:#cfd8ff; color:#2f3c8f; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
    .flash{ padding:12px 14px; border-radius:12px; margin-bottom:12px; font-weight:700; }
    .flash-err{ background:#ffecec; border:1px solid #f3bcbc; color:#8a0f0f; }
    form{ margin:0; }
</style>

<div class="wrap">
    @if(session('error'))
        <div class="flash flash-err">{{ session('error') }}</div>
    @endif

    @php
        $locked = $locked ?? ((($submission->status ?? '') === 'graded') || (bool)($submission->grade));
    @endphp

    <div class="card">
        <div class="title">{{ $locked ? 'Submission (Locked)' : 'Edit Submission' }}</div>

        <div class="muted">
            <div><strong>Assignment:</strong> {{ $submission->assignment?->title ?? 'Assignment removed' }}</div>
            <div><strong>Subject:</strong> {{ $submission->assignment?->subject?->name ?? 'N/A' }}</div>
            <div><strong>Last Submitted:</strong> {{ optional($submission->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                @if($submission->is_late) <span class="badge">Late</span> @endif
            </div>
            <div>
                <strong>Status:</strong>
                <span class="badge">{{ ucfirst($submission->status) }}</span>
                @if($locked) <span class="badge badge-lock">Locked (Graded)</span> @endif
            </div>

            @if($submission->grade)
                <div style="margin-top:8px;">
                    <strong>Marks:</strong> <span class="badge">{{ $submission->grade->marks }}</span>
                    @if(!empty($submission->grade->feedback))
                        <div class="muted" style="margin-top:6px; white-space:pre-wrap;">
                            <strong>Feedback:</strong> {{ $submission->grade->feedback }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if(!$locked)
            <form method="POST" action="{{ route('submissions.update', $submission->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <label>Replace PDF *</label>
                    <input type="file" name="file" accept="application/pdf" required>
                    @error('file') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                    <a class="btn btn-light" href="{{ route('submissions.download', $submission->id) }}">Download Current</a>
                    <a class="btn btn-light" href="{{ route('submissions.my') }}">Back</a>
                </div>
            </form>

            <div class="actions" style="margin-top:12px;">
                <form method="POST"
                      action="{{ route('submissions.destroy', $submission->id) }}"
                      onsubmit="return confirm('Delete this submission? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete Submission</button>
                </form>
            </div>
        @else
            <div class="muted" style="margin-top:6px;">
                This submission has been graded, so editing/deleting is disabled.
            </div>

            <div class="actions">
                <a class="btn btn-light" href="{{ route('submissions.download', $submission->id) }}">Download</a>
                <a class="btn btn-light" href="{{ route('submissions.my') }}">Back</a>
            </div>
        @endif
    </div>
</div>
@endsection
