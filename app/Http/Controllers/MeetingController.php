<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::orderBy('date_time', 'desc')->get();
        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        return view('meetings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_time' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'agenda' => 'required|string',
            'venue' => 'required|string',
            'organizer' => 'required|string',
            'attendees' => 'required|string',
            'absentees' => 'nullable|string',
            'transcription' => 'nullable|string'
        ]);

        // Convert comma-separated strings to arrays
        $validated['attendees'] = array_filter(array_map('trim', explode(',', $validated['attendees'])));
        if (!empty($validated['absentees'])) {
            $validated['absentees'] = array_filter(array_map('trim', explode(',', $validated['absentees'])));
        } else {
            $validated['absentees'] = [];
        }

        Meeting::create($validated);

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting created successfully.');
    }

    public function show(Meeting $meeting)
    {
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'date_time' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'agenda' => 'required|string',
            'venue' => 'required|string',
            'organizer' => 'required|string',
            'attendees' => 'required|string',
            'absentees' => 'nullable|string',
            'transcription' => 'nullable|string'
        ]);

        // Convert comma-separated strings to arrays
        $validated['attendees'] = array_filter(array_map('trim', explode(',', $validated['attendees'])));
        if (!empty($validated['absentees'])) {
            $validated['absentees'] = array_filter(array_map('trim', explode(',', $validated['absentees'])));
        } else {
            $validated['absentees'] = [];
        }

        $meeting->update($validated);

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully.');
    }
} 