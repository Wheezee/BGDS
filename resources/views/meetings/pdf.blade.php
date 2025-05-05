<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Details - {{ date('F j, Y', strtotime($meeting->date_time)) }}</title>
    @php
        use Illuminate\Support\Str;
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #6f42c1;
            margin-bottom: 5px;
        }
        .header h2 {
            font-weight: normal;
            color: #666;
            margin-top: 0;
            font-size: 18px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            color: #6f42c1;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-item .label {
            font-weight: bold;
            color: #563d7c;
        }
        .attendees-list, .absentees-list {
            list-style-type: none;
            padding-left: 0;
        }
        .attendees-list li, .absentees-list li {
            padding: 3px 0;
            border-bottom: 1px dotted #eee;
        }
        .attendees-list li:last-child, .absentees-list li:last-child {
            border-bottom: none;
        }
        .transcription {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #e9ecef;
            font-family: "Courier New", monospace;
            white-space: pre-wrap;
            margin-top: 10px;
        }
        .attachments-list {
            list-style-type: none;
            padding-left: 0;
        }
        .attachment-item {
            padding: 5px 0;
            border-bottom: 1px dotted #eee;
        }
        .attachment-item:last-child {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Meeting Details</h1>
        <h2>{{ date('F j, Y', strtotime($meeting->date_time)) }}</h2>
    </div>

    <div class="section">
        <h3 class="section-title">Meeting Information</h3>
        <div class="info-item">
            <span class="label">Date and Time:</span> {{ \Carbon\Carbon::parse($meeting->date_time)->format('F j, Y h:i A') }}
        </div>
        <div class="info-item">
            <span class="label">Duration:</span> {{ $meeting->duration ?? 'Not specified' }}
        </div>
        <div class="info-item">
            <span class="label">Venue:</span> {{ $meeting->venue }}
        </div>
        <div class="info-item">
            <span class="label">Organizer:</span> {{ $meeting->organizer }}
        </div>
        <div class="info-item">
            <span class="label">Agenda:</span> {{ $meeting->agenda }}
        </div>
    </div>

    <div class="section">
        <h3 class="section-title">Attendees</h3>
        <ul class="attendees-list">
            @foreach($meeting->attendees as $attendee)
                <li>{{ $attendee }}</li>
            @endforeach
        </ul>
    </div>

    @if($meeting->absentees && count($meeting->absentees) > 0)
    <div class="section">
        <h3 class="section-title">Absentees</h3>
        <ul class="absentees-list">
            @foreach($meeting->absentees as $absentee)
                <li>{{ $absentee }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($meeting->transcription)
    <div class="section">
        <h3 class="section-title">Meeting Transcription</h3>
        <div class="transcription">{{ $meeting->transcription }}</div>
    </div>
    @endif

    @if($meeting->transcription_file_path)
    <div class="section">
        <h3 class="section-title">Transcription File</h3>
        @if(Str::endsWith(strtolower($meeting->transcription_file_path), ['.jpg', '.jpeg', '.png', '.gif', '.bmp']))
            <div style="text-align: center; margin-bottom: 10px;">
                <img src="{{ public_path('storage/'.$meeting->transcription_file_path) }}" style="max-width: 100%; max-height: 500px;">
            </div>
        @elseif(Str::endsWith(strtolower($meeting->transcription_file_path), ['.pdf']))
            <p>
                <strong>PDF Transcription Available</strong><br>
                The transcription is available as a PDF file: <em>{{ $meeting->transcription_file_original_name ?? basename($meeting->transcription_file_path) }}</em><br>
                <em>(PDF files cannot be embedded in this document, but can be downloaded from the meeting page)</em>
            </p>
        @else
            <p>Transcription file is available but cannot be displayed in the PDF. File type: {{ pathinfo($meeting->transcription_file_path, PATHINFO_EXTENSION) }}</p>
        @endif
    </div>
    @endif

    @if($meeting->attachments && $meeting->attachments->count() > 0)
    <div class="section">
        <h3 class="section-title">Meeting Documents</h3>
        <ul class="attachments-list">
            @foreach($meeting->attachments as $attachment)
                <li class="attachment-item">
                    <strong>{{ $attachment->original_filename }}</strong>
                    @if($attachment->description)
                        <br>
                        <em>{{ $attachment->description }}</em>
                    @endif
                    <br>
                    <small>{{ $attachment->file_type_display }} | {{ $attachment->formatted_size }} | Added {{ $attachment->created_at->format('M j, Y') }}</small>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ date('F j, Y h:i A') }} | BGDS System</p>
    </div>
</body>
</html> 