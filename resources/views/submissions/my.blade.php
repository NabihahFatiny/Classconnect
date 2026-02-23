@extends('layouts.app')

@section('title', 'My Submissions')

@section('content')
<style>
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:800; color:#2d2d2d; margin-bottom:12px; }
    table{ width:100%; border-collapse:collapse; margin-top:8px; }
    th, td{ text-align:left; padding:12px 10px; border-bottom:1px solid #eee; font-size:14px; vertical-align:top; }
    th{ font-size:12px; color:#666; text-transform:uppercase; letter-spacing:.3px; }
    .badge{ display:inline-block; padding:4px 10px; border-radius:999px; background:#f2efdf; border:1px solid #e3ddc9; font-size:12px; font-weight:800; color:#6a5226; }
    .badge-lock{ background:#eef2ff; border-color:#cfd8ff; color:#2f3c8f; }
    .btn{ padding:8px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:800; text-decoration:none; display:inline-flex; gap:8px; background:#f2f2f2; color:#222; align-items:center; }
    .btn:hover{ background:#eaeaea; }
    .btn-danger{ background:#ffe9e9; color:#b00020; }
    .btn-danger:hover{ background:#ffdcdc; }
    .flash{ padding:12px 14px; border-radius:12px; margin-bottom:12px; font-weight:700; }
    .flash-ok{ background:#eaf6ea; border:1px solid #bfe3bf; color:#1f5f1f; }
    .flash-err{ background:#ffecec; border:1px solid #f3bcbc; color:#8a0f0f; }
    .actions{ display:flex; gap:8px; flex-wrap:wrap; }
    form{ display:inline; margin:0; }
</style>

@if(session('success'))
    <div class="flash flash-ok">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-err">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="title">My Submissions</div>

    <table>
        <thead>
        <tr>
            <th>Assignment</th>
            <th>Submitted At</th>
            <th>Status</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($submissions as $s)
            @php
                $locked = (($s->status ?? '') === 'graded') || (bool)($s->grade);
            @endphp

            <tr>
                <td>
                    <strong>{{ $s->assignment?->title ?? 'Assignment removed' }}</strong><br>
                    <span class="badge">{{ $s->assignment?->subject?->name ?? 'N/A' }}</span>
                </td>

                <td>
                    {{ optional($s->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                    @if($s->is_late) <span class="badge">Late</span> @endif
                </td>

                <td>
                    <span class="badge">{{ ucfirst($s->status) }}</span>
                    @if($locked) <span class="badge badge-lock">Locked</span> @endif
                </td>

                <td>
                    @if($s->grade)
                        <span class="badge">{{ $s->grade->marks }}</span>
                    @else
                        <span class="badge">Not graded</span>
                    @endif
                </td>

                <td>
                    <div class="actions">
                        <a class="btn" href="{{ route('submissions.download', $s->id) }}">Download</a>

                        @if(!$locked)
                            <a class="btn" href="{{ route('submissions.edit', $s->id) }}">Edit</a>

                            <form method="POST"
                                  action="{{ route('submissions.destroy', $s->id) }}"
                                  onsubmit="return confirm('Delete this submission? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        @else
                            <a class="btn" href="{{ route('submissions.edit', $s->id) }}">View</a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No submissions yet.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:14px;">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
