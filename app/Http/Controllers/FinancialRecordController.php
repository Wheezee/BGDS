<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialRecordController extends Controller
{
    /**
     * Display a listing of the financial records.
     */
    public function index(Request $request)
    {
        $query = FinancialRecord::query();
        
        // Handle filtering
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        // Handle sorting
        $sort = $request->input('sort', 'date');
        $direction = $request->input('direction', 'desc');
        
        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['date', 'transaction_type', 'amount', 'source_payee', 'category'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'date';
        }
        
        // Apply sorting
        $query->orderBy($sort, $direction);
        
        $records = $query->paginate(10);
        
        // Get unique categories for filter dropdown
        $categories = FinancialRecord::distinct()->pluck('category');
        
        return view('finance.index', compact('records', 'categories'));
    }

    /**
     * Show the form for creating a new financial record.
     */
    public function create()
    {
        return view('finance.create');
    }

    /**
     * Store a newly created financial record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'transaction_type' => 'required|in:Income,Expense',
            'amount' => 'required|numeric|min:0.01',
            'source_payee' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255',
        ]);
        
        // Add the user who recorded the transaction
        $user = Auth::user();
        $validated['recorded_by'] = $user->position . ' - ' . $user->name;
        
        FinancialRecord::create($validated);
        
        return redirect()->route('finance.index')->with('success', 'Financial record created successfully.');
    }

    /**
     * Display the specified financial record.
     */
    public function show(FinancialRecord $finance)
    {
        return view('finance.show', compact('finance'));
    }

    /**
     * Show the form for editing the specified financial record.
     */
    public function edit(FinancialRecord $finance)
    {
        return view('finance.edit', compact('finance'));
    }

    /**
     * Update the specified financial record in storage.
     */
    public function update(Request $request, FinancialRecord $finance)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'transaction_type' => 'required|in:Income,Expense',
            'amount' => 'required|numeric|min:0.01',
            'source_payee' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255',
        ]);
        
        $finance->update($validated);
        
        return redirect()->route('finance.index')->with('success', 'Financial record updated successfully.');
    }

    /**
     * Remove the specified financial record from storage.
     */
    public function destroy(FinancialRecord $finance)
    {
        $finance->delete();
        
        return redirect()->route('finance.index')->with('success', 'Financial record deleted successfully.');
    }
}
