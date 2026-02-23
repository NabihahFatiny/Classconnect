@extends('layouts.app')

@section('title', 'Submissions')

@section('content')
<style>
    .card { background:#fff; border-radius:16px; padding:18px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .title { font-size:22px; font-weight:800; color:#2d2d2d; margin-bottom:6px; }
    .muted { color:#777; font-size:13px; margin-bottom:12px; }
    table{ width:100%; border-collapse:collapse; margin-top:8px; }
    th, td{ text-align:left; padding:12px 10px; border-bottom:1px solid #eee; font-size:14px; }
    th{ font-size:12px; color:#666; text-transform:uppercase; letter-spacing:.3px; }
    .badge{ display:inline-block; padding:4px 10px; border-radius:999px; background:#f2efdf; border:1px solid #e3ddc9; font-size:12px; font-weight:800; color:#6a5226; }
    .btn{ padding:8px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:800; text-decoration:none; display:inline-flex; gap:8px; background:#f2f2f2; color:#222; }
    .btn:hover{ background:#eaeaea; }
</style>

<div class="card">
    <div class="title">Submissions</div>
    <div class="muted">
        <strong>Assignment:</strong> {{ $assignment->title }}
    </div>

    <table>
        <thead>
        <tr>
            <th>Student</th>
            <th>Submitted At</th>
            <th>Status</th>
            <th>Marks</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($submissions as $s)
            <tr>
                <td>
                    <strong>{{ $s->student->name ?? 'Student' }}</strong><br>
                    <span class="badge">{{ $s->student->username ?? '' }}</span>
                </td>
                <td>
                    {{ optional($s->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                    @if($s->is_late) <span class="badge">Late</span> @endif
                </td>
                <td><span class="badge">{{ ucfirst($s->status) }}</span></td>
                <td>
                    @if($s->grade)
                        <span class="badge">{{ $s->grade->marks }}</span>
                    @else
                        <span class="badge">-</span>
                    @endif
                </td>
                <td>
                    <a class="btn" href="{{ route('grading.show', [$assignment->id, $s->id]) }}">Edit / Grade</a>
                    <a class="btn" href="{{ route('submissions.download', $s->id) }}">Download</a>
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
