<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\FinancialRecord;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Resident Statistics
        $totalResidents = Resident::count();
        $maleResidents = Resident::where('sex', 'Male')->count();
        $femaleResidents = Resident::where('sex', 'Female')->count();

        // Financial Statistics
        $totalIncome = FinancialRecord::where('transaction_type', 'Income')->sum('amount');
        $totalExpenses = FinancialRecord::where('transaction_type', 'Expense')->sum('amount');

        // Meeting Statistics
        $totalMeetings = Meeting::count();
        $recentMeetings = Meeting::latest()->take(5)->get();

        // User Statistics
        $totalUsers = User::count();

        // Recent Financial Transactions
        $recentTransactions = FinancialRecord::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalResidents',
            'maleResidents',
            'femaleResidents',
            'totalIncome',
            'totalExpenses',
            'totalMeetings',
            'recentMeetings',
            'totalUsers',
            'recentTransactions'
        ));
    }
} 