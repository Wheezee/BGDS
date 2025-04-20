<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meeting Details</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-purple: #6f42c1;
            --light-purple: #e9ecef;
            --dark-purple: #563d7c;
        }
        
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: var(--primary-purple);
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            left: 0;
            top: 0;
        }
        
        #sidebar.active {
            margin-left: -250px;
        }
        
        #content {
            width: calc(100% - 250px);
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
            position: absolute;
            right: 0;
        }
        
        #content.active {
            width: 100%;
        }
        
        .sidebar-header {
            padding: 20px;
            background: var(--dark-purple);
        }
        
        .sidebar-link {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            transition: 0.3s;
        }
        
        .sidebar-link:hover {
            background: var(--dark-purple);
            color: #fff;
        }
        
        .sidebar-link i {
            margin-right: 10px;
        }
        
        #sidebarCollapse {
            background: var(--primary-purple);
            border-color: var(--primary-purple);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary-purple);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .meeting-details {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .meeting-details h4 {
            color: var(--primary-purple);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .meeting-details p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .meeting-details .label {
            font-weight: 600;
            color: var(--dark-purple);
        }

        .transcription {
            white-space: pre-wrap;
            overflow-x: auto;
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .attendees-list, .absentees-list {
            list-style-type: none;
            padding-left: 0;
        }

        .attendees-list li, .absentees-list li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .attendees-list li:last-child, .absentees-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>BGDS</h3>
            </div>

            <ul class="list-unstyled">
                <li>
                    <a href="/dashboard" class="sidebar-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="/residents" class="sidebar-link">
                        <i class="bi bi-people"></i> Residents
                    </a>
                </li>
                @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']))
                <li>
                    <a href="#" class="sidebar-link" data-bs-toggle="collapse" data-bs-target="#projectsSubmenu">
                        <i class="bi bi-kanban"></i> Plans and Projects
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="projectsSubmenu">
                        <li>
                            <a href="{{ route('projects.index', ['type' => 'BDP']) }}" class="sidebar-link">
                                <i class="bi bi-building"></i> BDP Projects
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('projects.index', ['type' => 'Calamity']) }}" class="sidebar-link">
                                <i class="bi bi-exclamation-triangle"></i> Calamity Projects
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']))
                <li>
                    <a href="/meetings" class="sidebar-link active">
                        <i class="bi bi-calendar-event"></i> Meetings
                    </a>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary', 'barangay_treasurer']))
                <li>
                    <a href="/finance" class="sidebar-link">
                        <i class="bi bi-cash-coin"></i> Financial Records
                    </a>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['superadmin']))
                <li>
                    <a href="/users" class="sidebar-link">
                        <i class="bi bi-people-fill"></i> User Management
                    </a>
                </li>
                @endif
                <li>
                    <a href="/userProfile" class="sidebar-link">
                        <i class="bi bi-person-circle"></i> User Profile
                    </a>
                </li>
            </ul>

            <div style="position: fixed; bottom: 0; width: 250px; padding: 20px; background: var(--dark-purple);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary me-3">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="m-0">Meeting Details</h1>
                    <div class="ms-auto user-profile">
                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Meeting Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="meeting-details">
                                    <p><span class="label">Date and Time:</span> {{ \Carbon\Carbon::parse($meeting->date_time)->format('F j, Y h:i A') }}</p>
                                    <p><span class="label">Duration:</span> {{ $meeting->duration ?? 'Not specified' }}</p>
                                    <p><span class="label">Venue:</span> {{ $meeting->venue }}</p>
                                    <p><span class="label">Organizer:</span> {{ $meeting->organizer }}</p>
                                    <p><span class="label">Agenda:</span> {{ $meeting->agenda }}</p>
                                    
                                    <h4 class="mt-4">Attendees</h4>
                                    <ul class="attendees-list">
                                        @foreach($meeting->attendees as $attendee)
                                            <li>{{ $attendee }}</li>
                                        @endforeach
                                    </ul>

                                    @if($meeting->absentees)
                                        <h4 class="mt-4">Absentees</h4>
                                        <ul class="absentees-list">
                                            @foreach($meeting->absentees as $absentee)
                                                <li>{{ $absentee }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if($meeting->transcription)
                                        <h4 class="mt-4">Meeting Transcription</h4>
                                        <div class="transcription">{{ $meeting->transcription }}</div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('meetings.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to List
                                    </a>
                                    @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']))
                                        <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil"></i> Edit Meeting
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });
    </script>
</body>
</html> 