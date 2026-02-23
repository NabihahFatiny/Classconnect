{{-- resources/views/assignments/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Assignment')

@section('content')
@php
    $id    = $assignment->getAttribute('id');
    $title = $assignment->getAttribute('title') ?? ('Assignment #'.$id);

    $dueAtRaw = $assignment->getAttribute('due_at');
    $dueAtVal = '';
    if (!empty($dueAtRaw)) {
        try {
            $dueAtVal = \Illuminate\Support\Carbon::parse($dueAtRaw)->format('Y-m-d\TH:i');
        } catch (\Throwable $e) {
            $dueAtVal = '';
        }
    }

    $attachPath = $assignment->getAttribute('attachment_path') ?? null;
@endphp

<style>
    .wrap { max-width: 950px; }
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:900; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:16px; }

    label { font-weight:800; font-size:13px; color:#333; display:block; margin-bottom:6px; }
    input, select, textarea {
        width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none;
    }
    textarea { min-height:120px; resize:vertical; }

    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
    @media (max-width: 720px){ .grid { grid-template-columns: 1fr; } }

    .row { margin-bottom:12px; }

    .btn{
        padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:800;
        text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-size:14px;
    }
    .btn-primary { background:#795E2E; color:#fff; }
    .btn-light { background:#f2f2f2; color:#222; }
    .btn-danger { background:#dc3545; color:#fff; }
    .btn:hover { opacity:.95; }

    .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }

    .error { color:#b00020; font-size:13px; margin-top:6px; }

    .box { border:1px solid #eee; border-radius:12px; padding:12px; background:#fff; }

    /* ===== Modal (same style as your 2nd pic) ===== */
    .cc-backdrop{
        position:fixed; inset:0;
        background:rgba(0,0,0,.35);
        display:none;
        align-items:center; justify-content:center;
        z-index:9999;
        padding:16px;
    }
    .cc-backdrop.show{ display:flex; }

    .cc-modal{
        width:min(620px, 95vw);
        background:#fff;
        border-radius:14px;
        box-shadow:0 8px 30px rgba(0,0,0,.2);
        padding:22px 22px 18px;
    }
    .cc-modal h3{
        margin:0 0 6px 0;
        font-size:20px;
        font-weight:900;
        color:#2d2d2d;
    }
    .cc-modal p{
        margin:0;
        color:#666;
        font-size:14px;
        line-height:1.4;
    }
    .cc-modal-actions{
        display:flex;
        justify-content:flex-end;
        gap:10px;
        margin-top:18px;
    }
    .cc-modal-btn{
        padding:10px 14px;
        border-radius:10px;
        border:none;
        cursor:pointer;
        font-weight:800;
        font-size:14px;
    }
    .cc-cancel{ background:#f2f2f2; color:#222; }
    .cc-continue{ background:#f2f2f2; color:#222; }
</style>

<div class="wrap">
    <div class="card">
        <div class="title">Edit Assignment</div>
        <div class="muted">Update assignment details. PDF upload is optional.</div>

        {{-- =========================
             UPDATE FORM (ONLY UPDATE)
             IMPORTANT: Do NOT put DELETE form inside this.
           ========================= --}}
        <form method="POST" action="{{ route('assignments.update', $id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid">
                <div class="row">
                    <label>Subject (optional)</label>
                    <select name="subject_id">
                        <option value="">-- Select Subject --</option>
                        @foreach(($subjects ?? []) as $s)
                            <option value="{{ $s->id }}"
                                @selected(old('subject_id', $assignment->getAttribute('subject_id')) == $s->id)>
                                {{ $s->name ?? ('Subject #'.$s->id) }}
                                @if(!empty($s->code)) ({{ $s->code }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <label>Due Date (optional)</label>
                    <input type="datetime-local" name="due_at" value="{{ old('due_at', $dueAtVal) }}">
                    @error('due_at') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <label>Title *</label>
                <input type="text" name="title" value="{{ old('title', $assignment->getAttribute('title')) }}">
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <label>Description (optional)</label>
                <textarea name="description">{{ old('description', $assignment->getAttribute('description')) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="row">
                    <label>Max Marks (optional)</label>
                    <input type="number" name="max_marks" min="0" max="1000"
                           value="{{ old('max_marks', $assignment->getAttribute('max_marks')) }}">
                    @error('max_marks') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <label>Attachment (PDF only, optional)</label>
                    <input type="file" name="attachment" accept="application/pdf">
                    <div class="muted" style="margin-top:6px;">Max 10MB. PDF only.</div>
                    @error('attachment') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <label>Current Attachment</label>
                <div class="box">
                    @if(!empty($attachPath))
                        <div class="muted" style="margin-bottom:10px;">A file is currently attached.</div>

                        {{-- IMPORTANT: no annoying "route not found" text --}}
                        @if(Route::has('assignments.download'))
                            <a class="btn btn-light" href="{{ route('assignments.download', $id) }}">
                                Download Current PDF
                            </a>
                        @endif
                    @else
                        <div class="muted">No attachment uploaded.</div>
                    @endif
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a class="btn btn-light" href="{{ route('assignments.index') }}">Back</a>
            </div>
        </form>

        {{-- =========================
             DELETE FORM (SEPARATE)
             Uses modal confirmation like your 2nd pic.
           ========================= --}}
        @if(Route::has('assignments.destroy'))
            <form id="deleteForm" method="POST" action="{{ route('assignments.destroy', $id) }}" style="margin-top:14px;">
                @csrf
                @method('DELETE')

                <button
                    type="button"
                    class="btn btn-danger"
                    id="openDeleteModal"
                    data-title="{{ $title }}"
                >
                    Delete Assignment
                </button>
            </form>
        @endif
    </div>
</div>

{{-- ===== Confirmation Modal (same look as your 2nd pic) ===== --}}
<div class="cc-backdrop" id="ccBackdrop" aria-hidden="true">
    <div class="cc-modal" role="dialog" aria-modal="true" aria-labelledby="ccModalTitle">
        <h3 id="ccModalTitle">Confirm deletion</h3>
        <p id="ccModalText">Are you sure you want to delete this assignment?</p>

        <div class="cc-modal-actions">
            <button type="button" class="cc-modal-btn cc-cancel" id="ccCancelBtn">Cancel</button>
            <button type="button" class="cc-modal-btn cc-continue" id="ccContinueBtn">Continue</button>
        </div>
    </div>
</div>

<script>
    (function () {
        const openBtn   = document.getElementById('openDeleteModal');
        const backdrop  = document.getElementById('ccBackdrop');
        const textEl    = document.getElementById('ccModalText');
        const cancelBtn = document.getElementById('ccCancelBtn');
        const contBtn   = document.getElementById('ccContinueBtn');
        const form      = document.getElementById('deleteForm');

        if (!openBtn || !backdrop || !cancelBtn || !contBtn || !form) return;

        function openModal() {
            const title = openBtn.getAttribute('data-title') || 'this assignment';
            textEl.textContent = `Are you sure you want to delete "${title}"?`;

            backdrop.classList.add('show');
            backdrop.setAttribute('aria-hidden', 'false');

            // focus on Cancel for safety
            setTimeout(() => cancelBtn.focus(), 0);
        }

        function closeModal() {
            backdrop.classList.remove('show');
            backdrop.setAttribute('aria-hidden', 'true');
            openBtn.focus();
        }

        openBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openModal();
        });

        cancelBtn.addEventListener('click', function () {
            closeModal();
        });

        contBtn.addEventListener('click', function () {
            // submit DELETE after confirmation
            form.submit();
        });

        // close if click outside modal
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) closeModal();
        });

        // close on ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && backdrop.classList.contains('show')) {
                closeModal();
            }
        });
    })();
</script>
@endsection
