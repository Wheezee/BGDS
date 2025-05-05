<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Financial Record</title>
    
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

        .form-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .form-section-title {
            color: var(--primary-purple);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }

        .btn-primary {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .btn-primary:hover {
            background-color: var(--dark-purple);
            border-color: var(--dark-purple);
        }

        .btn-outline-primary {
            color: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
            color: white;
        }

        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }

        .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }

        .input-group-text {
            background-color: var(--light-purple);
            border-color: var(--light-purple);
            color: var(--dark-purple);
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
                    <a href="/meetings" class="sidebar-link">
                        <i class="bi bi-calendar-event"></i> Meetings
                    </a>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary', 'barangay_treasurer']))
                <li>
                    <a href="/finance" class="sidebar-link active">
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
                    <h1 class="m-0">Edit Financial Record</h1>
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
                                <h5 class="mb-0">Edit Financial Transaction</h5>
                            </div>
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('finance.update', $finance->id) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <!-- Basic Information Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Basic Information</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date" class="form-label required-field">Date</label>
                                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                                       id="date" name="date" value="{{ old('date', $finance->date->format('Y-m-d')) }}" required>
                                                @error('date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="transaction_type" class="form-label required-field">Transaction Type</label>
                                                <select class="form-select @error('transaction_type') is-invalid @enderror" 
                                                        id="transaction_type" name="transaction_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="Income" {{ old('transaction_type', $finance->transaction_type) == 'Income' ? 'selected' : '' }}>Income</option>
                                                    <option value="Expense" {{ old('transaction_type', $finance->transaction_type) == 'Expense' ? 'selected' : '' }}>Expense</option>
                                                </select>
                                                @error('transaction_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amount and Category Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Amount and Category</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="amount" class="form-label required-field">Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" min="0.01" 
                                                           class="form-control @error('amount') is-invalid @enderror" 
                                                           id="amount" name="amount" value="{{ old('amount', $finance->amount) }}" required>
                                                </div>
                                                @error('amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="category" class="form-label required-field">Category</label>
                                                <select class="form-select @error('category') is-invalid @enderror" 
                                                        id="category" name="category" required>
                                                    <option value="">Select Category</option>
                                                    <option value="Barangay Funds" {{ old('category', $finance->category) == 'Barangay Funds' ? 'selected' : '' }}>Barangay Funds</option>
                                                    <option value="Aid" {{ old('category', $finance->category) == 'Aid' ? 'selected' : '' }}>Aid</option>
                                                    <option value="Health" {{ old('category', $finance->category) == 'Health' ? 'selected' : '' }}>Health</option>
                                                    <option value="Infrastructure" {{ old('category', $finance->category) == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                                    <option value="Education" {{ old('category', $finance->category) == 'Education' ? 'selected' : '' }}>Education</option>
                                                    <option value="Security" {{ old('category', $finance->category) == 'Security' ? 'selected' : '' }}>Security</option>
                                                    <option value="Other" {{ old('category', $finance->category) == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('category')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details Section -->
                                    <div class="form-section">
                                        <h4 class="form-section-title">Transaction Details</h4>
                                        <div class="mb-3">
                                            <label for="source_payee" class="form-label required-field">Source / Payee</label>
                                            <input type="text" class="form-control @error('source_payee') is-invalid @enderror" 
                                                   id="source_payee" name="source_payee" value="{{ old('source_payee', $finance->source_payee) }}" required>
                                            @error('source_payee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description / Purpose</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description', $finance->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="reference_number" class="form-label">Reference Number (OR#, Voucher#, etc.)</label>
                                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                                   id="reference_number" name="reference_number" value="{{ old('reference_number', $finance->reference_number) }}">
                                            @error('reference_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Back to List
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Transaction
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