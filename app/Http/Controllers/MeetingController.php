<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'transcription' => 'nullable|string',
            'transcription_file' => 'nullable|file|max:10240', // 10MB max per file
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
            'attachment_descriptions.*' => 'nullable|string'
        ]);

        // Convert comma-separated strings to arrays
        $validated['attendees'] = array_filter(array_map('trim', explode(',', $validated['attendees'])));
        if (!empty($validated['absentees'])) {
            $validated['absentees'] = array_filter(array_map('trim', explode(',', $validated['absentees'])));
        } else {
            $validated['absentees'] = [];
        }

        $meeting = Meeting::create($validated);

        // Handle transcription file if uploaded
        if ($request->hasFile('transcription_file')) {
            $file = $request->file('transcription_file');
            $filename = 'transcription_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('meeting_transcriptions', $filename, 'public');
            
            $meeting->update([
                'transcription_file_path' => $filePath,
                'transcription_file_original_name' => $file->getClientOriginalName()
            ]);
        }

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $index => $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('meeting_attachments', $filename, 'public');
                
                MeetingAttachment::create([
                    'meeting_id' => $meeting->id,
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'description' => $request->input('attachment_descriptions.' . $index, null)
                ]);
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting created successfully.');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load('attachments');
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $meeting->load('attachments');
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
            'transcription' => 'nullable|string',
            'transcription_file' => 'nullable|file|max:10240', // 10MB max per file
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
            'attachment_descriptions.*' => 'nullable|string'
        ]);

        // Convert comma-separated strings to arrays
        $validated['attendees'] = array_filter(array_map('trim', explode(',', $validated['attendees'])));
        if (!empty($validated['absentees'])) {
            $validated['absentees'] = array_filter(array_map('trim', explode(',', $validated['absentees'])));
        } else {
            $validated['absentees'] = [];
        }

        $meeting->update($validated);

        // Handle transcription file if uploaded
        if ($request->hasFile('transcription_file')) {
            // Delete old file if exists
            if ($meeting->transcription_file_path) {
                Storage::disk('public')->delete($meeting->transcription_file_path);
            }
            
            $file = $request->file('transcription_file');
            $filename = 'transcription_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('meeting_transcriptions', $filename, 'public');
            
            $meeting->update([
                'transcription_file_path' => $filePath,
                'transcription_file_original_name' => $file->getClientOriginalName()
            ]);
        }

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $index => $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('meeting_attachments', $filename, 'public');
                
                MeetingAttachment::create([
                    'meeting_id' => $meeting->id,
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'description' => $request->input('attachment_descriptions.' . $index, null)
                ]);
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        // Delete associated files from storage
        foreach ($meeting->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        // Delete transcription file if exists
        if ($meeting->transcription_file_path) {
            Storage::disk('public')->delete($meeting->transcription_file_path);
        }
        
        $meeting->delete();

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully.');
    }
    
    /**
     * Download a meeting attachment.
     */
    public function downloadAttachment(MeetingAttachment $attachment)
    {
        return Storage::disk('public')->download(
            $attachment->file_path, 
            $attachment->original_filename
        );
    }
    
    /**
     * Delete a meeting attachment.
     */
    public function deleteAttachment(MeetingAttachment $attachment)
    {
        $meeting = $attachment->meeting;
        
        // Delete the file from storage
        Storage::disk('public')->delete($attachment->file_path);
        
        // Delete the record
        $attachment->delete();
        
        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Attachment deleted successfully.');
    }

    /**
     * Download the meeting transcription file.
     */
    public function downloadTranscription(Meeting $meeting)
    {
        if (!$meeting->transcription_file_path) {
            abort(404, 'Transcription file not found');
        }
        
        return Storage::disk('public')->download(
            $meeting->transcription_file_path, 
            $meeting->transcription_file_original_name
        );
    }

    /**
     * Export meeting details to PDF.
     */
    public function exportPdf(Meeting $meeting)
    {
        $meeting->load('attachments');
        
        $pdf = PDF::loadView('meetings.pdf', compact('meeting'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        
        $filename = 'Meeting_' . date('Y-m-d', strtotime($meeting->date_time)) . '_' . Str::slug($meeting->agenda) . '.pdf';
        
        return $pdf->download($filename);
    }
} 