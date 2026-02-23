<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Models\File;
use App\Models\Lesson;
use App\Models\Subject;
use Exception; // <-- Import Storage here
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function isLecturer(): bool
    {
        return auth()->check() && (auth()->user()->user_type ?? '') === 'lecturer';
    }

    private function ensureLecturer(): void
    {
        if (! $this->isLecturer()) {
            abort(403, 'Only lecturers can create or modify lessons.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function lessonCreate()
    {
        $this->ensureLecturer();

        try {
            $subjects = Subject::all();

            return view('lessons.lessonCreate', compact('subjects'));
        } catch (Exception $e) {
            return view('lessons.lessonCreate')->with('error', 'An error occurred while loading the lesson creation page.');
        }
    }

    public function index(Request $request)
    {
        try {
            $lessons = Lesson::query()->with('subject');
            $subjects = Subject::all();
            if ($request->search) {
                $lessons->where('title', 'like', '%'.$request->search.'%');
            }
            if ($request->subject) {
                $lessons->where('subject_id', $request->subject);
            }
            $lessons = $lessons->paginate(6);

            return view('lessons.index', compact('lessons', 'subjects'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching lessons.');
        }
    }

    public function lessonView()
    {

        $subjects = Subject::all();

        return view('lessons.lessonCreate', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function file($id)
    {
        $file = File::findOrFail($id);

        if (empty($file->file_path)) {
            return redirect()->back()->with('error', 'File path not found.');
        }

        if (! Storage::disk('public')->exists($file->file_path)) {
            return redirect()->back()->with('error', 'File not found. Please contact the administrator.');
        }

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        $this->ensureLecturer();

        $request->validated();
        try {
            DB::transaction(function () use ($request) {
                $lesson = Lesson::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'subject_id' => $request->subject_id,
                ]);

                if ($request->hasFile('file_path')) {
                    foreach ($request->file('file_path') as $file) {
                        $filePath = $file->store('lessons', 'public');
                        File::create([
                            'file_path' => $filePath,
                            'file_name' => $file->getClientOriginalName(),
                            'lesson_id' => $lesson->id,
                        ]);
                    }
                }
            });

            session()->flash('success', 'Lesson created successfully.');

            return redirect()->route('lessons.index');
        } catch (Exception $e) {
            \Log::error('Error creating lesson: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['file_path']),
            ]);

            return redirect()->route('lessons.lessonCreate')->with('error', 'An error occurred while creating the lesson.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $lesson = Lesson::with(['subject', 'files'])->findOrFail($id);

            return view('lessons.lessonView', compact('lesson'));
        } catch (Exception $e) {
            \Log::error('Error displaying lesson: '.$e->getMessage(), [
                'exception' => $e,
                'lesson_id' => $id,
            ]);

            return redirect()->route('lessons.index')->with('error', 'An error occurred while loading the lesson.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->ensureLecturer();

        $lesson = Lesson::findOrFail($id);
        $subjects = Subject::all();

        return view('lessons.lessonEdit', compact('lesson', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLessonRequest $request, string $id)
    {
        $this->ensureLecturer();

        $request->validated();

        try {
            $lesson = Lesson::findOrFail($id);

            // Update lesson details
            $lesson->update([
                'title' => $request->title,
                'description' => $request->description,
                'subject_id' => $request->subject_id,
            ]);

            // Handle new file uploads if provided
            if ($request->hasFile('file_path')) {
                foreach ($request->file('file_path') as $file) {
                    $filePath = $file->store('lessons', 'public');

                    File::create([
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'lesson_id' => $lesson->id,
                    ]);
                }
            }

            return redirect()->route('lessons.edit', $lesson->id)->with('success', 'Lesson updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('lessons.edit', $lesson->id)->with('error', 'An error occurred while updating the lesson.');
        }
    }

    /**
     * Delete a specific file from a lesson.
     */
    public function deleteFile(string $id)
    {
        $this->ensureLecturer();

        try {
            $file = File::findOrFail($id);

            // Delete the physical file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete the database record
            $file->delete();

            return redirect()->back()->with('success', 'File deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the file.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->ensureLecturer();

        Lesson::destroy($id);

        return redirect()->back()->with('success', 'Lesson deleted successfully.');
    }
}
