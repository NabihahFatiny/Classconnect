@extends('layouts.app')

@section('title', 'Lessons')

@section('content')


    <div class="container">
        <div class="card p-4 mx-auto">
            <div class="mb-3">
                <a href="{{ route('lessons.index') }}" class="btn btn-light">
                    ← Back to Lessons
                </a>
            </div>

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

                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ $lesson->title }}" readonly >

                </div>

                <!-- Description -->
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" readonly>
    {{  $lesson->description  }}

            </textarea>
                    <div class="text-end small mt-1">143 words</div>


                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input class="form-control" readonly value="{{ $lesson->subject?->name ?? 'N/A' }}"></input>
                </div>

                <label class="form-label">Upload material</label>

                <!-- File input -->
                @if($lesson->files->isNotEmpty())
                @foreach ($lesson->files as $file)
                <div class="row mb-2 align-items-center well">
                    <div class="col-10">
                        <span>{{ $file->file_name }}</span>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('lessons.file', $file->id) }}" class="btn btn-primary btn-sm">Download</a>
                    </div>
                </div>
                @endforeach

                @else
                    <p>No files uploaded.</p>
                @endif
        </div>
    </div>
@endsection
