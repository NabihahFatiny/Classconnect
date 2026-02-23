@extends('layouts.app')

@section('title', 'Edit Discussion')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('discussions.show', $discussion) }}" style="color: #795E2E; text-decoration: none; margin-bottom: 20px; display: inline-block;">
                ← Back to Discussion
            </a>
        </div>
        <h1 style="color: #333; margin-bottom: 8px;">Edit Discussion</h1>
        <p style="color: #666; margin-bottom: 30px;">Subject: <strong style="color: #795E2E;">{{ $discussion->subject->name }}</strong></p>

        @if($errors->any())
            <div style="background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 16px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span style="font-size: 24px;">⚠️</span>
                    <strong style="font-size: 16px;">Content Warning</strong>
                </div>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li style="margin-bottom: 4px;">{{ $error }}</li>
                    @endforeach
                </ul>
                <p style="margin: 12px 0 0 0; font-size: 14px; font-style: italic;">
                    Please review your content and remove any inappropriate language before submitting.
                </p>
            </div>
        @endif

        <form action="{{ route('discussions.update', $discussion) }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 24px;">
                <label for="title" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Title <span style="color: #dc3545;">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $discussion->title) }}"
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
                    placeholder="Write your discussion content here...">{{ old('content', $discussion->content) }}</textarea>
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
                    <option value="1A" {{ old('class', $discussion->class) == '1A' ? 'selected' : '' }}>Class 1A</option>
                    <option value="1B" {{ old('class', $discussion->class) == '1B' ? 'selected' : '' }}>Class 1B</option>
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
                @if($discussion->image)
                    <div style="margin-bottom: 12px;">
                        <p style="color: #666; font-size: 14px; margin-bottom: 8px;">Current Image:</p>
                        <img src="{{ asset('storage/' . $discussion->image) }}" alt="Current Image" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e0e0e0; margin-bottom: 8px;">
                        <p style="color: #999; font-size: 12px;">Upload a new image to replace the current one.</p>
                    </div>
                @endif
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
                    Update Discussion
                </button>
                <a 
                    href="{{ route('discussions.show', $discussion) }}"
                    style="background: #e0e0e0; color: #333; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: background 0.3s;"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
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
    </script>
@endsection
