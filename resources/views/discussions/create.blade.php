@extends('layouts.app')

@section('title', 'Create Discussion')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('discussions.index') }}" style="color: #795E2E; text-decoration: none; margin-bottom: 20px; display: inline-block;">
                ← Back to Discussions
            </a>
        </div>
        <h1 style="color: #333; margin-bottom: 8px;">Create New Discussion</h1>
        <p style="color: #666; margin-bottom: 30px;">Subject: <strong style="color: #795E2E;">{{ $subject->name }}</strong></p>

        {{-- CSRF Token Error --}}
        @if($errors->has('_token'))
            <div style="background: #ffebee; border: 3px solid #f44336; color: #c62828; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(244, 67, 54, 0.3);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <span style="font-size: 32px;">⚠️</span>
                    <strong style="font-size: 18px;">Session Expired</strong>
                </div>
                <p style="margin: 0; font-size: 16px;">
                    {{ $errors->first('_token') }}
                </p>
                <p style="margin: 12px 0 0 0; font-size: 14px;">
                    <strong>Solution:</strong> Please refresh this page and try submitting again.
                </p>
            </div>
        @endif

        {{-- Always show errors if they exist (except CSRF token errors) --}}
        @if($errors->any() && !$errors->has('_token'))
            <div id="validation-error-box" style="background: #ffebee; border: 6px solid #f44336; color: #c62828; padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 8px 24px rgba(244, 67, 54, 0.5); animation: shake 0.8s; position: relative; z-index: 1000;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <span style="font-size: 48px;">🚫</span>
                    <strong style="font-size: 24px; color: #c62828; text-transform: uppercase; letter-spacing: 1px;">⚠️ VALIDATION FAILED - Inappropriate Content Detected ⚠️</strong>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 16px; border: 2px solid #f44336;">
                    <strong style="color: #c62828; display: block; margin-bottom: 12px; font-size: 18px;">Error Messages:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 24px; font-size: 18px; line-height: 2.2;">
                        @foreach($errors->all() as $error)
                            <li style="margin-bottom: 12px; font-weight: 700; color: #c62828; font-size: 18px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div style="margin: 20px 0 0 0; font-size: 18px; font-weight: 800; color: #c62828; text-align: center; padding: 16px; background: rgba(244, 67, 54, 0.2); border-radius: 8px; border: 3px solid #f44336;">
                    ⚠️ Your discussion contains inappropriate language. Please remove all violent or offensive words before submitting.
                </div>
            </div>
            <script>
                // Force scroll to error and highlight it
                document.addEventListener('DOMContentLoaded', function() {
                    const errorBox = document.getElementById('validation-error-box');
                    if (errorBox) {
                        errorBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        // Flash the error box
                        let flashCount = 0;
                        const flashInterval = setInterval(function() {
                            errorBox.style.borderColor = flashCount % 2 === 0 ? '#f44336' : '#ff9800';
                            flashCount++;
                            if (flashCount >= 6) {
                                clearInterval(flashInterval);
                                errorBox.style.borderColor = '#f44336';
                            }
                        }, 200);
                    }
                });
            </script>
        @endif

        <form id="discussion-form" action="{{ route('discussions.store') }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            @csrf
            <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}">

            <div style="margin-bottom: 24px;">
                <label for="title" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Title <span style="color: #dc3545;">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid {{ $errors->has('title') ? '#dc3545' : '#e0e0e0' }}; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                    placeholder="Enter discussion title..."
                >
                @error('title')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 6px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label for="content" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Content <span style="color: #dc3545;">*</span>
                </label>
                <textarea 
                    id="content" 
                    name="content" 
                    rows="10"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid {{ $errors->has('content') ? '#dc3545' : '#e0e0e0' }}; border-radius: 8px; font-size: 16px; resize: vertical; transition: border-color 0.3s;"
                    placeholder="Write your discussion content here...">{{ old('content') }}</textarea>
                @error('content')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 6px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            @if($isLecturer ?? false)
            <div style="margin-bottom: 24px;">
                <label for="class" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Select Class (Optional)
                </label>
                <select 
                    id="class" 
                    name="class" 
                    style="width: 100%; padding: 12px; border: 2px solid {{ $errors->has('class') ? '#dc3545' : '#e0e0e0' }}; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; background: white;"
                >
                    <option value="">All Classes (No restriction)</option>
                    <option value="1A" {{ old('class') == '1A' ? 'selected' : '' }}>Class 1A</option>
                    <option value="1B" {{ old('class') == '1B' ? 'selected' : '' }}>Class 1B</option>
                </select>
                <p style="color: #666; font-size: 14px; margin-top: 6px;">
                    Select a specific class for this discussion, or leave blank to make it accessible to all classes.
                </p>
                @error('class')
                    <div style="color: #dc3545; font-size: 14px; margin-top: 6px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @endif

            <div style="margin-bottom: 24px;">
                <label for="image" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Image (Optional)
                </label>
                <input 
                    type="file" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                    style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                    onchange="previewImage(this)"
                >
                <div id="imagePreview" style="margin-top: 12px; display: none;">
                    <img id="preview" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button 
                    type="submit"
                    style="background: #795E2E; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.3s;"
                >
                    Create Discussion
                </button>
                <a 
                    href="{{ route('discussions.index') }}"
                    style="background: #e0e0e0; color: #333; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: background 0.3s;"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
    <script>
        // CSRF Token Management
        document.addEventListener('DOMContentLoaded', function() {
            // Update CSRF token from meta tag
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                const csrfInput = document.getElementById('csrf-token');
                if (csrfInput) {
                    csrfInput.value = metaToken.getAttribute('content');
                }
            }

            // Handle form submission errors
            const form = document.getElementById('discussion-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Log form data before submission
                    const formData = new FormData(form);
                    console.log('Form submitting with:', {
                        title: formData.get('title'),
                        content: formData.get('content'),
                        title_length: formData.get('title')?.length || 0,
                        content_length: formData.get('content')?.length || 0,
                    });

                    // Refresh CSRF token before submission
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        const csrfInput = document.getElementById('csrf-token');
                        if (csrfInput) {
                            csrfInput.value = metaToken.getAttribute('content');
                        }
                    }
                });
            }
        });

        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewDiv.style.display = 'none';
            }
        }

        // Scroll to top if there are errors and show alert
        @if($errors->any() && !$errors->has('_token'))
            window.addEventListener('DOMContentLoaded', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                // Show alert with all errors
                const errors = @json($errors->all());
                if (errors.length > 0) {
                    alert('⚠️ VALIDATION ERROR:\n\n' + errors.join('\n\n'));
                }
            });
        @endif

        // Handle 419 errors - refresh page and show message
        @if(session('error') || (isset($errors) && $errors->has('_token')))
            alert('⚠️ Your session expired. Please refresh the page and try again.');
            window.location.reload();
        @endif
    </script>
@endsection

