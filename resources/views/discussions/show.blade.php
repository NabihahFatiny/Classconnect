@extends('layouts.app')

@section('title', $discussion->title)

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('discussions.index') }}" style="color: #795E2E; text-decoration: none; margin-bottom: 8px; display: inline-block;">
                ← Back to Discussions
            </a>
            <div style="color: #666; font-size: 14px;">
                Subject: <strong style="color: #795E2E;">{{ $discussion->subject->name }}</strong>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Discussion Post -->
        <div style="background: white; padding: 32px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 30px; border: 1px solid #f0f0f0;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; gap: 20px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
                        <h1 style="color: #2c3e50; margin: 0; font-size: 32px; font-weight: 700; line-height: 1.2;">{{ $discussion->title }}</h1>
                        @if($discussion->class)
                            <span style="background: {{ $canInteract ? '#795E2E' : '#999' }}; color: white; padding: 6px 16px; border-radius: 16px; font-size: 14px; font-weight: 600;">
                                {{ $discussion->class }}
                            </span>
                        @endif
                        @if(!$canInteract)
                            <span style="background: #fff3cd; color: #856404; padding: 6px 16px; border-radius: 16px; font-size: 13px; font-weight: 500; border: 1px solid #ffc107;">
                                🔒 View Only - Not Your Class
                            </span>
                        @endif
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 20px; color: #7a7a7a; font-size: 14px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0;">
                        <span style="display: flex; align-items: center; gap: 8px; font-weight: 500;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            {{ $discussion->user->name ?? 'Anonymous' }}
                        </span>
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            {{ $discussion->created_at->format('F d, Y \a\t g:i A') }}
                        </span>
                    </div>
                </div>
                @php
                    $currentUserId = auth()->id() ?? \App\Models\User::first()->id ?? 1;
                @endphp
                @if($discussion->user_id == $currentUserId && $canInteract)
                    <div style="display: flex; gap: 10px; flex-shrink: 0;">
                        <a href="{{ route('discussions.edit', $discussion) }}" style="background: #795E2E; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(121, 94, 46, 0.2);" onmouseover="this.style.background='#6a5127'; this.style.boxShadow='0 4px 8px rgba(121, 94, 46, 0.3)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#795E2E'; this.style.boxShadow='0 2px 4px rgba(121, 94, 46, 0.2)'; this.style.transform='translateY(0)'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this discussion? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);" onmouseover="this.style.background='#c82333'; this.style.boxShadow='0 4px 8px rgba(220, 53, 69, 0.3)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#dc3545'; this.style.boxShadow='0 2px 4px rgba(220, 53, 69, 0.2)'; this.style.transform='translateY(0)'">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <div style="color: #2c3e50; line-height: 1.8; font-size: 16px; margin-top: 20px;">
                {!! nl2br(e($discussion->content)) !!}
            </div>
            @if($discussion->image)
                <div style="margin-top: 20px;">
                    <img src="{{ asset('storage/' . $discussion->image) }}" alt="Discussion Image" style="max-width: 100%; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </div>
            @endif
        </div>

        <!-- Comments Section -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h2 style="color: #333; margin: 0 0 24px 0; font-size: 22px;">
                Comments ({{ $discussion->comments->count() }})
            </h2>

            <!-- Add Comment Form -->
            @if(!$canInteract && !($isLecturer ?? false))
                <div style="background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 16px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 24px;">🔒</span>
                        <div>
                            <strong style="font-size: 16px;">Read-Only Access</strong>
                            <p style="margin: 8px 0 0 0; font-size: 14px;">
                                This discussion belongs to class <strong>{{ $discussion->class }}</strong>. You can view it but cannot comment or interact since you belong to class <strong>{{ $userClass }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($canInteract && $errors->any())
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

            @if($canInteract)
            <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
                @csrf
                <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                <div style="margin-bottom: 12px;">
                    <textarea 
                        name="content" 
                        rows="4"
                        required
                        style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; resize: vertical;"
                        placeholder="Write your comment here...">{{ old('content') }}</textarea>
                </div>
                <div style="margin-bottom: 12px;">
                    <label for="photo" style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: #666; font-size: 14px; font-weight: 500;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        Add Photo (Optional)
                    </label>
                    <input 
                        type="file" 
                        name="photo" 
                        id="photo"
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        style="width: 100%; padding: 8px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
                    >
                    <p style="color: #999; font-size: 12px; margin-top: 4px;">
                        Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB, 2000x2000px)
                    </p>
                </div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button 
                        type="submit"
                        style="background: #795E2E; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#6a5127'" 
                        onmouseout="this.style.background='#795E2E'"
                    >
                        Post Comment
                    </button>
                    @if($discussion->comments->whereNull('parent_id')->count() > 0)
                        <button 
                            type="button"
                            onclick="toggleAllComments()"
                            id="toggle-all-comments"
                            style="background: #f5f5f5; color: #7a7a7a; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;"
                            onmouseover="this.style.background='#e0e0e0'; this.style.color='#333'" 
                            onmouseout="this.style.background='#f5f5f5'; this.style.color='#7a7a7a'"
                        >
                            <svg id="all-comments-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition: transform 0.3s;">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            <span id="all-comments-text">Hide All Comments</span>
                        </button>
                    @endif
                </div>
            </form>
            @endif

            <!-- Comments List -->
            @if($discussion->comments->whereNull('parent_id')->count() > 0)
                <div id="all-comments-container" style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($discussion->comments->whereNull('parent_id') as $comment)
                        <div id="comment-wrapper-{{ $comment->id }}">
                            @include('discussions.partials.comment', ['comment' => $comment, 'level' => 0, 'canInteract' => $canInteract, 'isLecturer' => $isLecturer ?? false])
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #999; text-align: center; padding: 40px 20px; font-size: 15px;">
                    No comments yet. Be the first to comment!
                </p>
            @endif

            <script>
            function toggleAllComments() {
                const container = document.getElementById('all-comments-container');
                const toggleButton = document.getElementById('toggle-all-comments');
                const commentsText = document.getElementById('all-comments-text');
                const commentsIcon = document.getElementById('all-comments-icon');
                
                if (container && toggleButton && commentsText && commentsIcon) {
                    const isHidden = container.style.display === 'none' || container.style.display === '';
                    
                    if (isHidden) {
                        container.style.display = 'flex';
                        commentsText.textContent = 'Hide All Comments';
                        commentsIcon.style.transform = 'rotate(0deg)';
                    } else {
                        container.style.display = 'none';
                        commentsText.textContent = 'Show All Comments';
                        commentsIcon.style.transform = 'rotate(180deg)';
                    }
                }
            }
            </script>
        </div>
    </div>
@endsection

