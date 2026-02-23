<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function select(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjectId = session('selected_subject_id');

        return view('subjects.select', compact('subjects', 'selectedSubjectId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        session(['selected_subject_id' => $request->subject_id]);

        return redirect()->route('discussions.index');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('selected_subject_id');

        return redirect()->route('subjects.select');
    }
}
