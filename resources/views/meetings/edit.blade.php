<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Meeting</title>
    
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

        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .btn-primary:hover {
            background-color: var(--dark-purple);
            border-color: var(--dark-purple);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            color: var(--dark-purple);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-purple);
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .submenu {
            padding-left: 40px;
            background: var(--dark-purple);
            margin-left: 10px;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        .submenu .sidebar-link {
            padding: 8px 20px;
            font-size: 0.9rem;
            position: relative;
        }
        
        .submenu .sidebar-link::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transform: translateY(-50%);
        }
        
        .submenu .sidebar-link:hover::before {
            background: white;
        }
        
        .submenu .sidebar-link i {
            font-size: 0.8rem;
            margin-right: 8px;
        }
        
        .sidebar-link[data-bs-toggle="collapse"] {
            position: relative;
        }
        
        .sidebar-link[data-bs-toggle="collapse"] i:last-child {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s;
        }
        
        .sidebar-link[data-bs-toggle="collapse"][aria-expanded="true"] i:last-child {
            transform: translateY(-50%) rotate(180deg);
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
                    <h1 class="m-0">Edit Meeting</h1>
                    <div class="ms-auto user-profile">
                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Edit Meeting Details</h5>
                                <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-light">
                                    <i class="bi bi-arrow-left"></i> Back to Details
                                </a>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('meetings.update', $meeting) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <!-- Basic Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Basic Information</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date_time" class="form-label required-field">Date & Time</label>
                                                <input type="datetime-local" class="form-control" 
                                                       id="date_time" name="date_time" 
                                                       value="{{ old('date_time', is_array($meeting->date_time) ? '' : $meeting->date_time->format('Y-m-d\TH:i')) }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                                                <input type="number" class="form-control" 
                                                       id="duration_minutes" name="duration_minutes" 
                                                       value="{{ old('duration_minutes', $meeting->duration_minutes) }}" 
                                                       min="1" placeholder="Enter duration in minutes">
                                                <small class="form-text text-muted">Enter the expected duration of the meeting in minutes</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Agenda Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Agenda</h4>
                                        <div class="mb-3">
                                            <label for="agenda" class="form-label required-field">Meeting Agenda</label>
                                            <textarea class="form-control" 
                                                      id="agenda" name="agenda" rows="3" required>{{ old('agenda', is_array($meeting->agenda) ? '' : $meeting->agenda) }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Location Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Location</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="venue" class="form-label required-field">Venue</label>
                                                <input type="text" class="form-control" 
                                                       id="venue" name="venue" 
                                                       value="{{ old('venue', is_array($meeting->venue) ? '' : $meeting->venue) }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="organizer" class="form-label required-field">Organizer</label>
                                                <input type="text" class="form-control" 
                                                       id="organizer" name="organizer" 
                                                       value="{{ old('organizer', is_array($meeting->organizer) ? '' : $meeting->organizer) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Additional Information</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="attendees" class="form-label required-field">Attendees</label>
                                                <input type="text" class="form-control @error('attendees') is-invalid @enderror" 
                                                       id="attendees" name="attendees" 
                                                       value="{{ is_array($meeting->attendees) ? implode(', ', $meeting->attendees) : $meeting->attendees }}" required>
                                                <small class="form-text text-muted">Enter attendee names separated by commas</small>
                                                @error('attendees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="absentees" class="form-label">Absentees</label>
                                                <input type="text" class="form-control @error('absentees') is-invalid @enderror" 
                                                       id="absentees" name="absentees" 
                                                       value="{{ is_array($meeting->absentees) ? implode(', ', $meeting->absentees) : $meeting->absentees }}">
                                                <small class="form-text text-muted">Enter absentee names separated by commas</small>
                                                @error('absentees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="transcription" class="form-label">Transcription</label>
                                            <textarea class="form-control" 
                                                      id="transcription" name="transcription" 
                                                      rows="5">{{ old('transcription', is_array($meeting->transcription) ? '' : $meeting->transcription) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Meeting
                                        </button>
                                    </div>
                                </form>
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