@extends('layouts.app')

@section('title', 'Lessons')

@section('content')

<div class="container py-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Top Bar -->
    <form action="{{ route("lessons.index") }}" method="get">
    <div class="d-flex align-items-center gap-3 mb-4">

        <select name="subject" class="form-select w-auto" onchange="this.form.submit()">
            <option  {{ request('subject') == '' ? 'selected' : '' }} value="">All Subjects</option>
            @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}"  {{ $subject->id == request('subject') ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>


        <div class="input-group rounded-pill flex-grow-1">
            <input name="search" type="text" class="form-control rounded-pill" placeholder="Search by title or author" value="{{ request('search') }}">
            <span class="input-group-text bg-transparent border-0">
           <button class="btn btn-light fw-semibold well-sm rounded-pill">
            <i class="bi bi-search"></i>
        </button>
            </span>
        </div>
        </form>
        @if(auth()->check() && auth()->user()->user_type === 'lecturer')
        <a class="btn btn-light fw-semibold" href="{{ route('lessons.lessonCreate') }}">
            <i class="bi bi-plus"></i> Lesson
        </a>
        @endif
    </div>

    <!-- Lessons Table -->
    <div class="bg-secondary-subtle p-3 rounded container-fluid">

        <div class="fw-bold mb-2 bg-light px-3 py-2 rounded">
            Lessons
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Subject</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lessons as $lesson)
                    <tr>
                        <td class="fw-semibold">{{ $lesson->title }}</td>
                        <td>{{ Str::limit($lesson->description, 100) }}</td>
                        <td>{{ $lesson->subject->name }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('lessons.show', $lesson->id) }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @if(auth()->check() && auth()->user()->user_type === 'lecturer')
                                <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form onsubmit="return confirm('Are you sure you want to delete?')" action="{{ route('lessons.destroy', [$lesson->id, request('subject'), request('search')]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-light btn-sm text-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No lessons found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
<div class="d-flex justify-content-end mx-5">
    {{ $lessons->links( 'pagination::bootstrap-4') }}
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection

