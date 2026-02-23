@extends('layouts.app')

@section('title', 'Edit Comment')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('discussions.show', $comment->discussion) }}" style="color: #795E2E; text-decoration: none; margin-bottom: 20px; display: inline-block;">
                ← Back to Discussion
            </a>
        </div>
        <h1 style="color: #333; margin-bottom: 8px;">Edit Comment</h1>
        <p style="color: #666; margin-bottom: 30px;">Discussion: <strong style="color: #795E2E;">{{ $comment->discussion->title }}</strong></p>

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
                    Please review your comment and remove any inappropriate language before submitting.
                </p>
            </div>
        @endif

        <form action="{{ route('comments.update', $comment) }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 24px;">
                <label for="content" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Comment <span style="color: #dc3545;">*</span>
                </label>
                <textarea 
                    id="content" 
                    name="content" 
                    rows="6"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; resize: vertical; transition: border-color 0.3s;"
                    placeholder="Write your comment here...">{{ old('content', $comment->content) }}</textarea>
            </div>

            <div style="margin-bottom: 24px;">
                <label for="photo" style="display: block; color: #333; font-weight: 600; margin-bottom: 8px;">
                    Photo (Optional)
                </label>
                @if($comment->photo)
                    <div style="margin-bottom: 12px;">
                        <p style="color: #666; font-size: 14px; margin-bottom: 8px;">Current Photo:</p>
                        <img src="{{ asset('storage/' . $comment->photo) }}" alt="Current Photo" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e0e0e0; margin-bottom: 8px;">
                        <p style="color: #999; font-size: 12px;">Upload a new photo to replace the current one.</p>
                    </div>
                @endif
                <input 
                    type="file" 
                    id="photo" 
                    name="photo" 
                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                    style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                    onchange="previewImage(this)"
                >
                <p style="color: #999; font-size: 12px; margin-top: 4px;">
                    Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB, 2000x2000px)
                </p>
                <div id="imagePreview" style="margin-top: 12px; display: none;">
                    <img id="preview" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button 
                    type="submit"
                    style="background: #795E2E; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.3s;"
                >
                    Update Comment
                </button>
                <a 
                    href="{{ route('discussions.show', $comment->discussion) }}"
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

