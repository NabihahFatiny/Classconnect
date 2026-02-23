<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        // Get the discussion and check class membership
        $discussion = \App\Models\Discussion::findOrFail($request->discussion_id);

        // Lecturers can comment on all discussions
        // Students can only comment on discussions from their own class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to comment.');
            }
            if ($discussion->class !== $userClass) {
                return redirect()->back()
                    ->with('error', 'You can only comment on discussions from your own class.');
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $photoPath = $file->storeAs('comments', $filename, 'public');

            if (! Storage::disk('public')->exists($photoPath)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Failed to upload photo. Please try again.']);
            }
        }

        Comment::create([
            'discussion_id' => $request->discussion_id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'photo' => $photoPath,
        ]);

        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }

    public function edit(Comment $comment): View|RedirectResponse
    {
        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        if ($comment->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Lecturers can edit comments on all discussions
        // Students can only edit comments on discussions from their own class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to edit comments.');
            }
            $discussion = $comment->discussion;
            if ($discussion->class !== $userClass) {
                abort(403, 'You can only edit comments on discussions from your own class.');
            }
        }

        $comment->load(['discussion', 'user']);

        return view('comments.edit', compact('comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        if ($comment->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Lecturers can update comments on all discussions
        // Students can only update comments on discussions from their own class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to update comments.');
            }
            $discussion = $comment->discussion;
            if ($discussion->class !== $userClass) {
                abort(403, 'You can only update comments on discussions from your own class.');
            }
        }

        // Handle photo upload
        $photoPath = $comment->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            // Upload new photo
            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $photoPath = $file->storeAs('comments', $filename, 'public');
        }

        $comment->update([
            'content' => $request->content,
            'photo' => $photoPath,
        ]);

        return redirect()->route('discussions.show', $comment->discussion)
            ->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $user = auth()->user();
        $userClass = $user->class ?? null;
        $isLecturer = $user->user_type === 'lecturer';

        if ($comment->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Lecturers can delete comments on all discussions
        // Students can only delete comments on discussions from their own class
        if (! $isLecturer) {
            if (! $userClass) {
                return redirect()->back()
                    ->with('error', 'You must be assigned to a class to delete comments.');
            }
            $discussion = $comment->discussion;
            if ($discussion->class !== $userClass) {
                abort(403, 'You can only delete comments on discussions from your own class.');
            }
        }

        $discussion = $comment->discussion;

        // Delete associated photo if exists
        if ($comment->photo && Storage::disk('public')->exists($comment->photo)) {
            Storage::disk('public')->delete($comment->photo);
        }

        $comment->delete();

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Comment deleted successfully!');
    }
}
