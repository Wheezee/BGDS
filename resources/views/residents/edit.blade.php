<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Resident</title>
    
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

        .info-section {
            margin-bottom: 2rem;
        }

        .info-section-title {
            color: var(--dark-purple);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-purple);
        }

        .info-label {
            font-weight: 600;
            color: var(--dark-purple);
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #495057;
            padding: 0.5rem;
            background-color: var(--light-purple);
            border-radius: 5px;
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
                    <h1 class="m-0">Edit Resident</h1>
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
                                <h5 class="mb-0">Edit Resident Information</h5>
                                <a href="/residents" class="btn btn-light">Back to Residents</a>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('residents.update', $resident->id) }}" method="POST">
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
                                    
                                    <!-- Personal Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Personal Information</h4>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $resident->last_name }}" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $resident->first_name }}" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $resident->middle_name }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="suffix" class="form-label">Suffix/Ext.</label>
                                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{ $resident->suffix }}">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="place_of_birth" class="form-label">Place of Birth</label>
                                                <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="{{ $resident->place_of_birth }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $resident->date_of_birth->format('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="age" class="form-label">Age</label>
                                                <input type="number" class="form-control" id="age" name="age" value="{{ $resident->age }}" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="sex" class="form-label">Sex</label>
                                                <select class="form-select" id="sex" name="sex" required>
                                                    <option value="">Select</option>
                                                    <option value="Male" {{ $resident->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ $resident->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="civil_status" class="form-label">Civil Status</label>
                                                <select class="form-select" id="civil_status" name="civil_status" required>
                                                    <option value="">Select</option>
                                                    <option value="Single" {{ $resident->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                    <option value="Married" {{ $resident->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                    <option value="Widowed" {{ $resident->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                    <option value="Divorced" {{ $resident->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="citizenship" class="form-label">Citizenship</label>
                                                <input type="text" class="form-control" id="citizenship" name="citizenship" value="{{ $resident->citizenship }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="occupation" class="form-label">Occupation</label>
                                                <input type="text" class="form-control" id="occupation" name="occupation" value="{{ $resident->occupation }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="labor_status" class="form-label">Labor Status</label>
                                                <select class="form-select" id="labor_status" name="labor_status">
                                                    <option value="">Select</option>
                                                    <option value="Employed" {{ $resident->labor_status == 'Employed' ? 'selected' : '' }}>Employed</option>
                                                    <option value="Unemployed" {{ $resident->labor_status == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                                    <option value="PWD" {{ $resident->labor_status == 'PWD' ? 'selected' : '' }}>PWD</option>
                                                    <option value="OFW" {{ $resident->labor_status == 'OFW' ? 'selected' : '' }}>OFW</option>
                                                    <option value="Solo Parent" {{ $resident->labor_status == 'Solo Parent' ? 'selected' : '' }}>Solo Parent</option>
                                                    <option value="OSY" {{ $resident->labor_status == 'OSY' ? 'selected' : '' }}>OSY</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="contact_number" class="form-label">Contact Number</label>
                                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $resident->contact_number }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ $resident->email }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Family Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Family Information</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="mother_name" class="form-label">Mother's Name</label>
                                                <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ $resident->mother_name }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="father_name" class="form-label">Father's Name</label>
                                                <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $resident->father_name }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="family_group" class="form-label">Family Group</label>
                                                <input type="text" class="form-control" id="family_group" name="family_group" value="{{ $resident->family_group }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="household_id" class="form-label">Household ID</label>
                                                <input type="text" class="form-control" id="household_id" name="household_id" value="{{ $resident->household_id }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Additional Information</h4>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="education" class="form-label">Education</label>
                                                <select class="form-select" id="education" name="education">
                                                    <option value="">Select</option>
                                                    <option value="Elementary" {{ $resident->education == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                                    <option value="High School" {{ $resident->education == 'High School' ? 'selected' : '' }}>High School</option>
                                                    <option value="Vocational" {{ $resident->education == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                                    <option value="College" {{ $resident->education == 'College' ? 'selected' : '' }}>College</option>
                                                    <option value="Post Graduate" {{ $resident->education == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="blood_type" class="form-label">Blood Type</label>
                                                <select class="form-select" id="blood_type" name="blood_type">
                                                    <option value="">Select</option>
                                                    <option value="A+" {{ $resident->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                                    <option value="A-" {{ $resident->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                                    <option value="B+" {{ $resident->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                                    <option value="B-" {{ $resident->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                                    <option value="AB+" {{ $resident->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                    <option value="AB-" {{ $resident->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                    <option value="O+" {{ $resident->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                                    <option value="O-" {{ $resident->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="height" class="form-label">Height</label>
                                                <input type="text" class="form-control" id="height" name="height" value="{{ $resident->height }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="weight" class="form-label">Weight</label>
                                                <input type="text" class="form-control" id="weight" name="weight" value="{{ $resident->weight }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="skin_complexion" class="form-label">Skin Complexion</label>
                                                <input type="text" class="form-control" id="skin_complexion" name="skin_complexion" value="{{ $resident->skin_complexion }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="program_participation" class="form-label">Program Participation</label>
                                                <select class="form-select" id="program_participation" name="program_participation">
                                                    <option value="">Select</option>
                                                    <option value="MCCT" {{ $resident->program_participation == 'MCCT' ? 'selected' : '' }}>MCCT</option>
                                                    <option value="4PS" {{ $resident->program_participation == '4PS' ? 'selected' : '' }}>4PS</option>
                                                    <option value="UCT" {{ $resident->program_participation == 'UCT' ? 'selected' : '' }}>UCT</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Voter Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Voter Information</h4>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="voter" class="form-label">Voter</label>
                                                <select class="form-select" id="voter" name="voter">
                                                    <option value="">Select</option>
                                                    <option value="Yes" {{ $resident->voter == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No" {{ $resident->voter == 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="resident_voter" class="form-label">Resident Voter</label>
                                                <select class="form-select" id="resident_voter" name="resident_voter">
                                                    <option value="">Select</option>
                                                    <option value="Yes" {{ $resident->resident_voter == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No" {{ $resident->resident_voter == 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="year_last_voted" class="form-label">Year Last Voted</label>
                                                <input type="text" class="form-control" id="year_last_voted" name="year_last_voted" value="{{ $resident->year_last_voted }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Identification Numbers Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Identification Numbers</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="philsys_number" class="form-label">PhilSys Card Number</label>
                                                <input type="text" class="form-control" id="philsys_number" name="philsys_number" value="{{ $resident->philsys_number }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Save Changes
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