<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Financial Records</title>
    
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

        .summary-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .summary-card.bg-success {
            background-color: #28a745 !important;
        }

        .summary-card.bg-danger {
            background-color: #dc3545 !important;
        }

        .summary-card.bg-primary {
            background-color: var(--primary-purple) !important;
        }

        .table th {
            background-color: var(--light-purple);
            color: var(--dark-purple);
        }

        .table td.income {
            color: #28a745;
        }

        .table td.expense {
            color: #dc3545;
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
                    <h1 class="m-0">Financial Records</h1>
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
                                <h5 class="mb-0">Financial Transactions</h5>
                                <div>
                                    <a href="{{ route('finance.create') }}" class="btn btn-light">Add New Transaction</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Filter Section -->
                                <div class="mb-4">
                                    <button class="btn btn-outline-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                        <i class="bi bi-funnel"></i> Filter Records
                                    </button>
                                    
                                    <div class="collapse" id="filterCollapse">
                                        <div class="card card-body">
                                            <form method="GET" action="{{ route('finance.index') }}">
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <label for="transaction_type" class="form-label">Transaction Type</label>
                                                        <select class="form-select" id="transaction_type" name="transaction_type">
                                                            <option value="">All Types</option>
                                                            <option value="Income" {{ request('transaction_type') == 'Income' ? 'selected' : '' }}>Income</option>
                                                            <option value="Expense" {{ request('transaction_type') == 'Expense' ? 'selected' : '' }}>Expense</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="category" class="form-label">Category</label>
                                                        <select class="form-select" id="category" name="category">
                                                            <option value="">All Categories</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="start_date" class="form-label">Start Date</label>
                                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="end_date" class="form-label">End Date</label>
                                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <a href="{{ route('finance.index') }}" class="btn btn-secondary">Clear Filters</a>
                                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                <!-- Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="card summary-card bg-success text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">Total Income</h5>
                                                <h3 class="card-text">₱{{ number_format($records->where('transaction_type', 'Income')->sum('amount'), 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card summary-card bg-danger text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">Total Expenses</h5>
                                                <h3 class="card-text">₱{{ number_format($records->where('transaction_type', 'Expense')->sum('amount'), 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card summary-card bg-primary text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">Net Balance</h5>
                                                <h3 class="card-text">₱{{ number_format($records->where('transaction_type', 'Income')->sum('amount') - $records->where('transaction_type', 'Expense')->sum('amount'), 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Financial Records Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <a href="{{ route('finance.index', ['sort' => 'date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except('sort', 'direction')) }}" class="text-dark text-decoration-none">
                                                        Date
                                                        @if(request('sort') == 'date')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('finance.index', ['sort' => 'transaction_type', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except('sort', 'direction')) }}" class="text-dark text-decoration-none">
                                                        Type
                                                        @if(request('sort') == 'transaction_type')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('finance.index', ['sort' => 'amount', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except('sort', 'direction')) }}" class="text-dark text-decoration-none">
                                                        Amount
                                                        @if(request('sort') == 'amount')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('finance.index', ['sort' => 'source_payee', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except('sort', 'direction')) }}" class="text-dark text-decoration-none">
                                                        Source/Payee
                                                        @if(request('sort') == 'source_payee')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="{{ route('finance.index', ['sort' => 'category', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except('sort', 'direction')) }}" class="text-dark text-decoration-none">
                                                        Category
                                                        @if(request('sort') == 'category')
                                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($records as $record)
                                                <tr>
                                                    <td>{{ $record->date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge {{ $record->transaction_type == 'Income' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $record->transaction_type }}
                                                        </span>
                                                    </td>
                                                    <td class="{{ $record->transaction_type == 'Income' ? 'income' : 'expense' }}">
                                                        ₱{{ number_format($record->amount, 2) }}
                                                    </td>
                                                    <td>{{ $record->source_payee }}</td>
                                                    <td>{{ $record->category }}</td>
                                                    <td>{{ $record->description ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('finance.show', $record->id) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('finance.edit', $record->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="{{ route('finance.destroy', $record->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No financial records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $records->links() }}
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