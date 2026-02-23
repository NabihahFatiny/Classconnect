@extends('layouts.app')

@section('title', 'Lessons')

@section('content')
    {{-- popup dialog box for upload success --}}
    @session('success')
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endsession
    {{-- popup dialog box for upload error --}}
    @session('error')
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endsession

    <div class="container">
        <div class="card p-4 mx-auto">

            <form action="{{ route('lessons.update', $lesson->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ $lesson->title }}">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" >
    {{ $lesson->description }}

            </textarea>
                    <div class="text-end small mt-1">143 words</div>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>
                {{-- subject --}}
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select">
                        <option value="">Please select subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $lesson->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach

                    </select>
                    @error('subject_id')
                        <div class="text-danger mt-1">{{ $message }}

                        </div>
                    @enderror



                </div>

                {{-- Display existing files --}}
                @if($lesson->files->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Current Files:</label>
                        <div class="list-group">
                            @foreach($lesson->files as $file)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-file-earmark"></i>
                                        <span class="ms-2">{{ $file->file_name }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('lessons.file', $file->id) }}" class="btn btn-sm btn-primary me-2">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="deleteFile({{ $file->id }}, '{{ $file->file_name }}')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- files --}}

                                <div class="mb-3">
                                    <label for="fileupload" class="form-label">Upload Files</label>
                                    <input type="file" class="form-control" id="fileupload" name="file_path[]" multiple>
                                </div>
                                 @error('file_path.*')
                    <div class="text-danger mt-1">{{ $message }}

                    </div>
                @enderror
                                <div class="mb-3">
                                    <div id="file-list"></div>
                                </div>

                <!-- File input -->

                {{-- <label class="form-label">Upload material</label>
                <input type="file" class="form-control" name="file_path"> --}}

                {{-- button --}}
                <div class="mt-4 row justify-content-end gap-2 me-2">
                    <a href="{{ route('lessons.index') }}" class="btn btn-secondary col-md-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success col-md-2">Update</button>
                </div>
        </div>
        </form>






    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const $input = $('#fileupload');
    const $list = $('#file-list');
    const files = [];

    const formatSize = bytes =>
        bytes < 1024 ? `${bytes} B` : `${(bytes / 1024).toFixed(1)} Kb`;

    $input.on('change', function () {
        Array.from(this.files).forEach(file => {
            const exists = files.some(f => f.name === file.name && f.size === file.size);
            if (!exists) {
                files.push(file);
            }
        });
        this.value = null;
        renderFileList();
    });

    $list.on('click', '.remove', function () {
        const index = $(this).data('idx');
        files.splice(index, 1);
        renderFileList();
    });

    function renderFileList() {
        const dataTransfer = new DataTransfer();
        $list.empty();

        files.forEach((file, index) => {
            dataTransfer.items.add(file);
            $list.append(`
                <div class="file-row">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatSize(file.size)}</span>
                    <span class="icon remove" data-idx="${index}">✕</span>
                </div>
            `);
        });

        $input[0].files = dataTransfer.files;
    }

    // Delete file function
    function deleteFile(fileId, fileName) {
        if (confirm(`Are you sure you want to delete "${fileName}"?`)) {
            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/lessons/files/${fileId}`;

            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
