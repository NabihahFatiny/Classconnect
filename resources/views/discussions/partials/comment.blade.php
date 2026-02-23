@php
    $currentUserId = auth()->id() ?? \App\Models\User::first()->id ?? 1;
    $canInteract = $canInteract ?? true; // Default to true if not passed
@endphp

<div id="comment-{{ $comment->id }}" style="padding: 24px; background: #ffffff; border-radius: 8px; border-left: 4px solid #795E2E; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s;" onmouseover="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.12)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08)'">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 14px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #795E2E 0%, #6a5127 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px; box-shadow: 0 2px 4px rgba(121, 94, 46, 0.2);">
                {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <strong style="color: #2c3e50; font-size: 16px; font-weight: 600; display: block; margin-bottom: 4px;">
                    {{ $comment->user->name ?? 'Anonymous' }}
                    @if($comment->parent_id)
                        <span style="color: #7a7a7a; font-weight: 400; font-size: 14px;">replied to {{ $comment->parent->user->name ?? 'Anonymous' }}</span>
                    @endif
                </strong>
                <span style="color: #7a7a7a; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
        @if($comment->user_id == $currentUserId && $canInteract)
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('comments.edit', $comment) }}" style="background: #795E2E; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(121, 94, 46, 0.2);" onmouseover="this.style.background='#6a5127'; this.style.boxShadow='0 2px 6px rgba(121, 94, 46, 0.3)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#795E2E'; this.style.boxShadow='0 1px 3px rgba(121, 94, 46, 0.2)'; this.style.transform='translateY(0)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(220, 53, 69, 0.2);" onmouseover="this.style.background='#c82333'; this.style.boxShadow='0 2px 6px rgba(220, 53, 69, 0.3)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#dc3545'; this.style.boxShadow='0 1px 3px rgba(220, 53, 69, 0.2)'; this.style.transform='translateY(0)'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        @endif
    </div>
    <div style="color: #2c3e50; line-height: 1.7; margin-bottom: 12px; font-size: 15px;">
        {!! nl2br(e($comment->content)) !!}
    </div>
    @if($comment->photo)
        <div style="margin-top: 16px;">
            <img src="{{ asset('storage/' . $comment->photo) }}" alt="Comment Photo" style="max-width: 100%; max-height: 400px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        </div>
    @endif

    <!-- Reply Button and Replies Toggle -->
    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #f0f0f0; display: flex; align-items: center; gap: 16px;">
        @if($canInteract)
        <button onclick="toggleReplyForm({{ $comment->id }})" style="background: none; border: none; color: #795E2E; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            Reply
        </button>
        @endif
        @if($comment->replies->count() > 0)
            <button onclick="toggleReplies({{ $comment->id }})" id="toggle-replies-{{ $comment->id }}" style="background: none; border: none; color: #7a7a7a; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.color='#795E2E'; this.style.background='#f5f5f5'" onmouseout="this.style.color='#7a7a7a'; this.style.background='none'">
                <svg id="replies-icon-{{ $comment->id }}" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition: transform 0.3s; transform: rotate(180deg);">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                <span id="replies-text-{{ $comment->id }}">Show {{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}</span>
            </button>
        @endif
    </div>

    <!-- Reply Form (Hidden by default) -->
    <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
        <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="discussion_id" value="{{ $comment->discussion_id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div style="margin-bottom: 12px;">
                <textarea 
                    name="content" 
                    rows="3"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; resize: vertical;"
                    placeholder="Write your reply...">{{ old('content') }}</textarea>
            </div>
            <div style="display: flex; gap: 8px;">
                <button 
                    type="submit"
                    style="background: #795E2E; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.2s;"
                    onmouseover="this.style.background='#6a5127'" 
                    onmouseout="this.style.background='#795E2E'"
                >
                    Post Reply
                </button>
                <button 
                    type="button"
                    onclick="toggleReplyForm({{ $comment->id }})"
                    style="background: #e0e0e0; color: #333; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.2s;"
                    onmouseover="this.style.background='#d0d0d0'" 
                    onmouseout="this.style.background='#e0e0e0'"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Nested Replies -->
    @if($comment->replies->count() > 0)
        <div id="replies-container-{{ $comment->id }}" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
            @foreach($comment->replies as $reply)
                @include('discussions.partials.comment', ['comment' => $reply, 'level' => $level + 1, 'canInteract' => $canInteract])
            @endforeach
        </div>
    @endif
</div>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            form.style.display = 'none';
        }
    }
}

function toggleReplies(commentId) {
    const container = document.getElementById('replies-container-' + commentId);
    const repliesText = document.getElementById('replies-text-' + commentId);
    const repliesIcon = document.getElementById('replies-icon-' + commentId);
    
    if (container && repliesText && repliesIcon) {
        const isHidden = container.style.display === 'none' || container.style.display === '';
        
        if (isHidden) {
            container.style.display = 'block';
            const replyCount = container.children.length;
            repliesText.textContent = 'Hide ' + replyCount + ' ' + (replyCount === 1 ? 'reply' : 'replies');
            repliesIcon.style.transform = 'rotate(0deg)';
        } else {
            container.style.display = 'none';
            const replyCount = container.children.length;
            repliesText.textContent = 'Show ' + replyCount + ' ' + (replyCount === 1 ? 'reply' : 'replies');
            repliesIcon.style.transform = 'rotate(180deg)';
        }
    }
}

</script>

