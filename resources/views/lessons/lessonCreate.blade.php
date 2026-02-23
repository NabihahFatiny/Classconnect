@extends('layouts.app')

@section('title', 'Lessons')

@section('content')
    {{-- popup dialog box for upload success --}}
      @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    {{-- popup dialog box for upload error --}}
    @session('error')
        <div class="alert alert-danger mt-3">
            {{ $value }}
        </div>
    @endsession
    @if(isset($connected) && !$connected)
    <div class="alert alert-danger text-center">
        ⚠️ Connection lost. Please try again later.
    </div>
    @endif
    <div class="container">
        <div class="card p-4 mx-auto">

            <form action="{{ route('lessons.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">
    {{ old('description') }}

            </textarea>
                    <div class="text-end small mt-1">143 words</div>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select">
                        <option value="">Please select subject</option>
                        @isset($subjects)
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                        @endisset
                    </select>
                    @error('subject_id')
                        <div class="text-danger mt-1">{{ $message }}
                        </div>
                    @enderror
                </div>



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
                    <button type="submit" id="submitBtn" class="btn btn-success col-md-2">save</button>
                </div>

                <!-- Offline indicator -->
                <div id="offlineIndicator" class="alert alert-warning mt-3 d-none">
                    <strong>⚠️ You are offline.</strong> Please check your internet connection. You cannot submit while offline.
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

    // Offline/Online detection
    const submitBtn = document.getElementById('submitBtn');
    const offlineIndicator = document.getElementById('offlineIndicator');

    function updateOnlineStatus() {
        if (navigator.onLine) {
            // User is online
            submitBtn.disabled = false;
            submitBtn.classList.remove('disabled');
            offlineIndicator.classList.add('d-none');
        } else {
            // User is offline
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled');
            offlineIndicator.classList.remove('d-none');
        }
    }

    // Check status on page load
    updateOnlineStatus();

    // Listen for online/offline events
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

</script>
@endsection
