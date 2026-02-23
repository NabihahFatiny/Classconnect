@extends('layouts.app')

@section('title', 'Create Assignment')

@section('content')
<style>
    .wrap { max-width: 850px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:800; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:16px; }

    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
    @media (max-width: 720px){ .grid { grid-template-columns: 1fr; } }

    label { font-weight:700; font-size:13px; color:#333; display:block; margin-bottom:6px; }
    input, select, textarea {
        width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none;
    }
    textarea { min-height:120px; resize:vertical; }

    .row { margin-bottom:12px; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }

    .btn {
        padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:800;
        text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-size:14px;
    }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .error { color:#b00020; font-size:13px; margin-top:6px; }
</style>

<div class="wrap">
    <div class="card">
        <div class="title">Create Assignment</div>
        <div class="muted">Lecturer can create and publish assignments here.</div>

        <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid">
                <div class="row">
                    <label>Subject (optional)</label>
                    <select name="subject_id">
                        <option value="">-- Select Subject --</option>
                        @foreach(($subjects ?? []) as $s)
                            <option value="{{ $s->id }}" @selected(old('subject_id') == $s->id)>
                                {{ $s->name ?? $s->subject_name ?? ('Subject #'.$s->id) }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <label>Due Date (optional)</label>
                    <input type="datetime-local" name="due_at" value="{{ old('due_at') }}">
                    @error('due_at') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <label>Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Mobile App Coursework 1">
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <label>Description (optional)</label>
                <textarea name="description" placeholder="Instructions, requirements, submission format...">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="row">
                    <label>Max Marks (optional)</label>
                    <input type="number" name="max_marks" min="0" max="1000" value="{{ old('max_marks') }}" placeholder="e.g. 100">
                    @error('max_marks') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <label>Attachment (optional)</label>
                    <input type="file" name="attachment">
                    @error('attachment') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-light" href="{{ route('assignments.index') }}">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
