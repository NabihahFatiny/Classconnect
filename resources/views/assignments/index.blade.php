{{-- resources/views/assignments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Assignments')

@section('content')
@php
    $assignments = $assignments ?? collect();

    $isPaginator =
        $assignments instanceof \Illuminate\Contracts\Pagination\Paginator ||
        $assignments instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    $list  = $isPaginator ? $assignments : collect($assignments);
    $count = $isPaginator ? ($assignments->total() ?? $list->count()) : $list->count();

    $userType = auth()->check() ? (auth()->user()->user_type ?? '') : '';
@endphp

<style>
    .page-head{
        display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;
        margin-bottom:18px;
    }
    .page-title{ font-size:22px; font-weight:800; color:#2d2d2d; }
    .page-sub{ font-size:13px; color:#6b6b6b; margin-top:4px; }

    .card{
        background:#fff; border-radius:16px; padding:18px;
        box-shadow:0 2px 8px rgba(0,0,0,.08);
    }

    .toolbar{
        display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
        margin-bottom:12px;
    }

    .search-box{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .input{
        width:340px; max-width:100%;
        padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#fff; outline:none;
    }

    .btn{
        padding:10px 14px; border-radius:10px; border:none; cursor:pointer;
        font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:8px;
        font-size:14px;
    }
    .btn-primary{ background:#795E2E; color:#fff; }
    .btn-primary:hover{ opacity:.92; }
    .btn-light{ background:#f2f2f2; color:#222; }
    .btn-light:hover{ background:#eaeaea; }
    .btn-danger{ background:#dc3545; color:#fff; }
    .btn-danger:hover{ opacity:.92; }

    .muted{ color:#777; font-size:13px; }

    .flash{
        padding:12px 14px; border-radius:12px; margin-bottom:12px;
        background:#eaf6ea; border:1px solid #bfe3bf; color:#1f5f1f; font-weight:700;
    }
    .flash-error{
        background:#fee; border:1px solid #f3b3b3; color:#8a1f1f;
    }

    table{ width:100%; border-collapse:collapse; margin-top:8px; }
    th, td{
        text-align:left; padding:12px 10px; border-bottom:1px solid #eee; vertical-align:top;
        color:#2d2d2d; font-size:14px;
    }
    th{
        font-size:12px; color:#666; text-transform:uppercase; letter-spacing:.3px;
    }

    .badge{
        display:inline-block; padding:4px 10px; border-radius:999px;
        background:#f2efdf; border:1px solid #e3ddc9;
        font-size:12px; font-weight:800; color:#6a5226;
        margin-right:6px;
    }

    .actions{ display:flex; gap:8px; flex-wrap:wrap; }

    .empty{
        padding:16px; border:1px dashed #cfc6ad; border-radius:14px;
        background:#fffdf5; color:#6a5226; font-weight:800; margin-top:10px;
    }

    .pagination-wrap{ margin-top:14px; }

    /* ===== Delete Modal (Two-Stage Confirm) ===== */
    .modal-overlay{
        position:fixed; inset:0;
        background:rgba(0,0,0,.35);
        display:none;
        align-items:center; justify-content:center;
        z-index:2000;
        padding:16px;
    }
    .modal-card{
        width:100%;
        max-width:520px;
        background:#fff;
        border-radius:16px;
        padding:18px;
        box-shadow:0 10px 30px rgba(0,0,0,.18);
    }
    .modal-title{
        font-size:18px;
        font-weight:900;
        color:#2d2d2d;
        margin-bottom:6px;
    }
    .modal-body{
        color:#555;
        font-size:14px;
        line-height:1.45;
        margin-bottom:14px;
        white-space:pre-wrap;
    }
    .modal-actions{
        display:flex;
        gap:10px;
        justify-content:flex-end;
        flex-wrap:wrap;
    }
</style>

<div class="page-head">
    <div>
        <div class="page-title">Assignments</div>
        <div class="page-sub">View, submit, and manage assignments.</div>
    </div>

    <div class="actions">
        @if($userType === 'student' && Route::has('submissions.my'))
            <a class="btn btn-light" href="{{ route('submissions.my') }}">My Submissions</a>
        @endif

        @if($userType === 'lecturer' && Route::has('assignments.create'))
            <a class="btn btn-primary" href="{{ route('assignments.create') }}">+ Create Assignment</a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="flash">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-error">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="toolbar">
        @if(Route::has('assignments.index'))
            <form class="search-box" method="GET" action="{{ route('assignments.index') }}" style="margin:0;">
                <input class="input" type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search assignment title / description...">
                <button class="btn btn-light" type="submit">Search</button>
                @if(request('q'))
                    <a class="btn btn-light" href="{{ route('assignments.index') }}">Clear</a>
                @endif
            </form>
        @else
            <div class="muted">Search disabled (route not found).</div>
        @endif

        <div class="muted">
            Total: <strong>{{ $count }}</strong>
        </div>
    </div>

    @if($list->count() === 0)
        <div class="empty">
            No assignments found.
            <div class="muted" style="margin-top:6px;">
                @if($userType === 'lecturer')
                    Create one using “Create Assignment”.
                @else
                    Wait for your lecturer to publish assignments.
                @endif
            </div>
        </div>
    @else
        <table>
            <thead>
            <tr>
                <th style="width:45%;">Assignment</th>
                <th style="width:20%;">Subject</th>
                <th style="width:20%;">Due Date</th>
                <th style="width:15%;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($list as $a)
                @php
                    $id    = $a->getAttribute('id');
                    $title = $a->getAttribute('title') ?? ('Assignment #' . $id);
                    $desc  = $a->getAttribute('description') ?? null;
                    $due   = $a->getAttribute('due_at') ?? null;

                    $mySubmission = null;
                    if ($userType === 'student' && isset($a->submissions) && $a->submissions instanceof \Illuminate\Support\Collection) {
                        $mySubmission = $a->submissions->first();
                    }
                @endphp

                <tr>
                    <td>
                        <div style="font-weight:900;">{{ $title }}</div>
                        <div class="muted">
                            {{ \Illuminate\Support\Str::limit($desc ?: 'No description provided.', 110) }}
                        </div>

                        {{-- Student: show submission status here --}}
                        @if($userType === 'student' && $mySubmission)
                        <div class="muted" style="margin-top:6px;">
                            <span class="badge">{{ ucfirst($mySubmission->status ?? 'submitted') }}</span>
                            @if(($mySubmission->status ?? '') === 'graded')
                                <span class="badge">Locked</span>
                            @endif
                            Submitted: {{ optional($mySubmission->submitted_at)->format('d M Y, h:i A') }}
                            @if(!empty($mySubmission->is_late))
                                <span class="badge">Late</span>
                            @endif
                            @if($mySubmission->relationLoaded('grade') && $mySubmission->grade)
                                <span class="badge">Marks: {{ $mySubmission->grade->marks }}</span>
                            @endif
                        </div>
                    @endif

                    </td>

                    <td>
                        @if(isset($a->subject) && $a->subject)
                            <span class="badge">{{ $a->subject->name }} ({{ $a->subject->code }})</span>
                        @else
                            <span class="badge">N/A</span>
                        @endif
                    </td>

                    <td>
                        @if(!empty($due))
                            <span class="badge">
                                {{ \Illuminate\Support\Carbon::parse($due)->format('d M Y, h:i A') }}
                            </span>
                        @else
                            <span class="muted">Not set</span>
                        @endif
                    </td>

                    <td>
                        <div class="actions">
                            @if(Route::has('assignments.show'))
                                <a class="btn btn-light" href="{{ route('assignments.show', $id) }}">View</a>
                            @endif

                            {{-- Student actions --}}
                            @if($userType === 'student' && Route::has('submissions.create'))
                                @if($mySubmission)
                                    @php
                                        $locked = (($mySubmission->status ?? '') === 'graded')
                                            || ($mySubmission->relationLoaded('grade') && $mySubmission->grade);
                                    @endphp

                                    @if(Route::has('submissions.download'))
                                        <a class="btn btn-light"
                                        href="{{ route('submissions.download', $mySubmission->id) }}?v={{ optional($mySubmission->updated_at)->timestamp ?? time() }}">
                                            Download
                                        </a>
                                    @endif

                                    @if(Route::has('submissions.edit'))
                                        <a class="btn {{ $locked ? 'btn-light' : 'btn-primary' }}"
                                        href="{{ route('submissions.edit', $mySubmission->id) }}">
                                            {{ $locked ? 'View (Graded)' : 'Edit' }}
                                        </a>
                                    @endif
                                @else
                                    <a class="btn btn-primary" href="{{ route('submissions.create', $id) }}">
                                        Submit
                                    </a>
                                @endif
                            @endif


                            {{-- Lecturer actions --}}
                            @if($userType === 'lecturer')
                                @if(Route::has('assignments.edit'))
                                    <a class="btn btn-light" href="{{ route('assignments.edit', $id) }}">Edit</a>
                                @endif

                                @if(Route::has('assignments.submissions'))
                                    <a class="btn btn-primary" href="{{ route('assignments.submissions', $id) }}">
                                        Submissions
                                    </a>
                                @endif

                                {{-- IMPORTANT: Delete is now a button that triggers 2-step modal --}}
                                @if(Route::has('assignments.destroy'))
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        data-delete-form="delete-assignment-{{ $id }}"
                                        data-assignment-title="{{ $title }}"
                                        onclick="openDeleteModal(this)"
                                    >
                                        Delete
                                    </button>

                                    {{-- Hidden real DELETE form --}}
                                    <form id="delete-assignment-{{ $id }}"
                                          method="POST"
                                          action="{{ route('assignments.destroy', $id) }}"
                                          style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if($isPaginator && method_exists($assignments, 'links'))
            <div class="pagination-wrap">
                {{ $assignments->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Two-stage confirmation modal --}}
<div class="modal-overlay" id="deleteModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="modal-title" id="deleteModalTitle">Confirm delete</div>
        <div class="modal-body" id="deleteModalBody"></div>

        <div class="modal-actions">
            <button type="button" class="btn btn-light" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn btn-light" id="deleteContinueBtn" onclick="deleteModalNext()">Continue</button>
            <button type="button" class="btn btn-danger" id="deleteFinalBtn" onclick="confirmDelete()" style="display:none;">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
    let _deleteFormId = null;
    let _deleteTitle = '';
    let _deleteStage = 1;

    function openDeleteModal(btn){
        _deleteFormId = btn.dataset.deleteForm || null;
        _deleteTitle  = btn.dataset.assignmentTitle || 'this assignment';
        _deleteStage  = 1;

        renderDeleteModal();

        const modal = document.getElementById('deleteModal');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }

    function renderDeleteModal(){
        const titleEl = document.getElementById('deleteModalTitle');
        const bodyEl  = document.getElementById('deleteModalBody');
        const contBtn = document.getElementById('deleteContinueBtn');
        const finalBtn= document.getElementById('deleteFinalBtn');

        if (_deleteStage === 1){
            titleEl.textContent = 'Confirm deletion';
            bodyEl.textContent  = `Are you sure you want to delete "${_deleteTitle}"?`;
            contBtn.style.display = 'inline-flex';
            finalBtn.style.display = 'none';
        } else {
            titleEl.textContent = 'Delete permanently';
            bodyEl.textContent  = `This action cannot be undone.\n\nClick "Delete" to permanently remove "${_deleteTitle}".`;
            contBtn.style.display = 'none';
            finalBtn.style.display = 'inline-flex';
        }
    }

    function deleteModalNext(){
        _deleteStage = 2;
        renderDeleteModal();
    }

    function confirmDelete(){
        if (!_deleteFormId) return;
        const form = document.getElementById(_deleteFormId);
        if (form) form.submit();
    }

    function closeDeleteModal(){
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');

        _deleteFormId = null;
        _deleteTitle  = '';
        _deleteStage  = 1;
    }

    // Close modal when clicking outside card
    document.addEventListener('click', function(e){
        const modal = document.getElementById('deleteModal');
        if (modal.style.display === 'flex' && e.target === modal) {
            closeDeleteModal();
        }
    });

    // ESC closes modal
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endsection
