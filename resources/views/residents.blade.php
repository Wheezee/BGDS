<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Residents</title>
    
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

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary-purple);
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 2rem;
            margin: 10px 0;
            color: var(--dark-purple);
        }

        .stat-card p {
            color: #6c757d;
            margin: 0;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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
                    <a href="/residents" class="sidebar-link active">
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
                    <h1 class="m-0">Dashboard Overview</h1>
                    <div class="ms-auto user-profile">
                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Resident List</h5>
                                <div>
                                    <a href="{{ route('residents.all-info') }}" class="btn btn-light me-2">Show All Information</a>
                                    <a href="/add_resident" class="btn btn-light">Add New Resident</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Basic Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <a href="{{ route('residents.index', ['sort' => 'last_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                                        Name
                                                        @if(request('sort') == 'last_name')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('residents.index', ['sort' => 'place_of_birth', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                                        Address
                                                        @if(request('sort') == 'place_of_birth')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('residents.index', ['sort' => 'contact_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                                        Contact
                                                        @if(request('sort') == 'contact_number')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('residents.index', ['sort' => 'age', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                                        Age
                                                        @if(request('sort') == 'age')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($residents as $resident)
                                            <tr>
                                                <td>{{ $resident->full_name }}</td>
                                                <td>{{ $resident->place_of_birth }}</td>
                                                <td>{{ $resident->contact_number }}</td>
                                                <td>{{ $resident->age }}</td>
                                                <td>
                                                    <a href="{{ route('residents.show', $resident->id) }}" class="btn btn-sm btn-info">View</a>
                                                    <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="{{ route('residents.destroy', $resident->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this resident?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Detailed Table (Collapsible) -->
                                <div class="collapse mt-4" id="detailedTable">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Last Name</th>
                                                    <th>First Name</th>
                                                    <th>Middle Name</th>
                                                    <th>Suffix</th>
                                                    <th>Place of Birth</th>
                                                    <th>Date of Birth</th>
                                                    <th>Age</th>
                                                    <th>Sex</th>
                                                    <th>Civil Status</th>
                                                    <th>Citizenship</th>
                                                    <th>Occupation</th>
                                                    <th>Labor Status</th>
                                                    <th>Contact Number</th>
                                                    <th>Email</th>
                                                    <th>Education</th>
                                                    <th>Mother's Name</th>
                                                    <th>Father's Name</th>
                                                    <th>PhilSys Card #</th>
                                                    <th>Household ID #</th>
                                                    <th>Program Participation</th>
                                                    <th>Family Group</th>
                                                    <th>Blood Type</th>
                                                    <th>Height</th>
                                                    <th>Weight</th>
                                                    <th>Skin Complexion</th>
                                                    <th>Voter</th>
                                                    <th>Resident Voter</th>
                                                    <th>Year Last Voted</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($residents as $resident)
                                                <tr>
                                                    <td>{{ $resident->id }}</td>
                                                    <td>{{ $resident->last_name }}</td>
                                                    <td>{{ $resident->first_name }}</td>
                                                    <td>{{ $resident->middle_name ?? 'N/A' }}</td>
                                                    <td>{{ $resident->suffix ?? 'N/A' }}</td>
                                                    <td>{{ $resident->place_of_birth }}</td>
                                                    <td>{{ $resident->date_of_birth->format('Y-m-d') }}</td>
                                                    <td>{{ $resident->age }}</td>
                                                    <td>{{ $resident->sex }}</td>
                                                    <td>{{ $resident->civil_status }}</td>
                                                    <td>{{ $resident->citizenship }}</td>
                                                    <td>{{ $resident->occupation ?? 'N/A' }}</td>
                                                    <td>{{ $resident->labor_status ?? 'N/A' }}</td>
                                                    <td>{{ $resident->contact_number }}</td>
                                                    <td>{{ $resident->email ?? 'N/A' }}</td>
                                                    <td>{{ $resident->education ?? 'N/A' }}</td>
                                                    <td>{{ $resident->mother_name ?? 'N/A' }}</td>
                                                    <td>{{ $resident->father_name ?? 'N/A' }}</td>
                                                    <td>{{ $resident->philsys_number ?? 'N/A' }}</td>
                                                    <td>{{ $resident->household_id ?? 'N/A' }}</td>
                                                    <td>{{ $resident->program_participation ?? 'N/A' }}</td>
                                                    <td>{{ $resident->family_group ?? 'N/A' }}</td>
                                                    <td>{{ $resident->blood_type ?? 'N/A' }}</td>
                                                    <td>{{ $resident->height ?? 'N/A' }}</td>
                                                    <td>{{ $resident->weight ?? 'N/A' }}</td>
                                                    <td>{{ $resident->skin_complexion ?? 'N/A' }}</td>
                                                    <td>{{ $resident->voter ?? 'N/A' }}</td>
                                                    <td>{{ $resident->resident_voter ?? 'N/A' }}</td>
                                                    <td>{{ $resident->year_last_voted ?? 'N/A' }}</td>
                                                    <td>{{ $resident->created_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>{{ $resident->updated_at->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
