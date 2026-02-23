@extends('layouts.app')

@section('title', 'Submit Assignment')

@section('content')
<style>
    .wrap { max-width: 850px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:800; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:16px; }
    .row { margin-bottom:12px; }
    label { font-weight:700; font-size:13px; color:#333; display:block; margin-bottom:6px; }
    input { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none; }
    .btn { padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:800; text-decoration:none; display:inline-flex; gap:8px; }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .error { color:#b00020; font-size:13px; margin-top:6px; }
    .badge { display:inline-block; padding:4px 10px; border-radius:999px; background:#f2efdf; border:1px solid #e3ddc9; font-size:12px; font-weight:800; color:#6a5226; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
    .flash{ padding:12px 14px; border-radius:12px; margin-bottom:12px; font-weight:700; }
    .flash-err{ background:#ffecec; border:1px solid #f3bcbc; color:#8a0f0f; }
</style>

<div class="wrap">
    @if(session('error'))
        <div class="flash flash-err">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="title">Submit Assignment</div>
        <div class="muted">
            <div><strong>Title:</strong> {{ $assignment->title }}</div>
            <div><strong>Subject:</strong>
                {{ $assignment->subject?->name ?? 'N/A' }}
                @if($assignment->subject?->code) ({{ $assignment->subject->code }}) @endif
            </div>
            <div><strong>Due:</strong>
                @if($assignment->due_at) <span class="badge">{{ $assignment->due_at->format('d M Y, h:i A') }}</span>
                @else <span class="badge">Not set</span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('submissions.store', $assignment->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <label>Upload PDF *</label>
                <input type="file" name="file" accept="application/pdf" required>
                @error('file') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Submit</button>
                <a class="btn btn-light" href="{{ route('assignments.index') }}">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
