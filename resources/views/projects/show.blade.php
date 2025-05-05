<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Details</title>
    
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

        .progress {
            height: 25px;
        }

        .project-info {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .project-info h5 {
            color: var(--dark-purple);
            margin-bottom: 15px;
        }

        .project-info p {
            margin-bottom: 10px;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
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
                    <a href="#" class="sidebar-link active" data-bs-toggle="collapse" data-bs-target="#projectsSubmenu">
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
                    <a href="/meetings" class="sidebar-link">
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
                    <h1 class="m-0">Project Details</h1>
                    <div class="ms-auto">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil"></i> Update
                        </a>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Project Overview -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Project Overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="project-info">
                                    <h5>Basic Information</h5>
                                    <p><strong>Project Name:</strong> {{ $project->project_name }}</p>
                                    <p><strong>Project Type:</strong> {{ $project->project_type }} ({{ $project->project_type == 'BDP' ? '20%' : '5%' }})</p>
                                    <p><strong>Assigned Committee:</strong> {{ $project->assigned_committee }}</p>
                                </div>

                                <div class="project-info">
                                    <h5>Timeline</h5>
                                    <p><strong>Start Date:</strong> {{ $project->start_date->format('M d, Y') }}</p>
                                    <p><strong>Target End Date:</strong> {{ $project->target_end_date->format('M d, Y') }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="status-badge bg-{{ 
                                            $project->status == 'Completed' ? 'success' : 
                                            ($project->status == 'Ongoing' ? 'primary' : 
                                            ($project->status == 'Delayed' ? 'warning' : 'secondary')) 
                                        }}">
                                            {{ $project->status }}
                                        </span>
                                    </p>
                                </div>

                                <div class="project-info">
                                    <h5>Progress</h5>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $project->progress }}%;" 
                                             aria-valuenow="{{ $project->progress }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $project->progress }}%
                                        </div>
                                    </div>
                                </div>

                                @if($project->description)
                                    <div class="project-info">
                                        <h5>Description</h5>
                                        <p>{{ $project->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Project Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Project Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> Update Project
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?')">
                                            <i class="bi bi-trash"></i> Delete Project
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Project Timeline -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Project Timeline</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6>Project Started</h6>
                                            <p>{{ $project->start_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6>Current Status</h6>
                                            <p>{{ $project->status }}</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6>Target Completion</h6>
                                            <p>{{ $project->target_end_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
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