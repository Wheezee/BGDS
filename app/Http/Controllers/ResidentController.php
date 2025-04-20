<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResidentController extends Controller
{
    /**
     * Display a listing of the residents.
     */
    public function index(Request $request)
    {
        $query = Resident::query();
        
        // Handle sorting
        $sort = $request->input('sort', 'last_name');
        $direction = $request->input('direction', 'asc');
        
        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['last_name', 'first_name', 'place_of_birth', 'contact_number', 'age', 'sex', 'civil_status'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'last_name';
        }
        
        // Apply sorting
        $query->orderBy($sort, $direction);
        
        $residents = $query->paginate(10);
        
        return view('residents', compact('residents'));
    }

    /**
     * Show the form for creating a new resident.
     */
    public function create()
    {
        return view('add_resident');
    }

    /**
     * Store a newly created resident in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Divorced',
            'citizenship' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'labor_status' => 'nullable|in:Employed,Unemployed,PWD,OFW,Solo Parent,OSY',
            'contact_number' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'education' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'philsys_number' => 'nullable|string|max:255',
            'household_id' => 'nullable|string|max:255',
            'program_participation' => 'nullable|in:MCCT,4PS,UCT',
            'family_group' => 'nullable|string|max:255',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|between:0,999.99',
            'weight' => 'nullable|numeric|between:0,999.99',
            'skin_complexion' => 'nullable|in:Fair,Medium,Light Brown,Brown,Black',
            'voter' => 'nullable|in:Yes,No',
            'resident_voter' => 'nullable|in:Yes,No',
            'year_last_voted' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Resident::create($request->all());

        return redirect('residents');
    }

    /**
     * Display the specified resident.
     */
    public function show(Resident $resident)
    {
        return view('residents.show', compact('resident'));
    }

    /**
     * Show the form for editing the specified resident.
     */
    public function edit(Resident $resident)
    {
        return view('residents.edit', compact('resident'));
    }

    /**
     * Update the specified resident in storage.
     */
    public function update(Request $request, Resident $resident)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Divorced',
            'citizenship' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'labor_status' => 'nullable|in:Employed,Unemployed,PWD,OFW,Solo Parent,OSY',
            'contact_number' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'education' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'philsys_number' => 'nullable|string|max:255',
            'household_id' => 'nullable|string|max:255',
            'program_participation' => 'nullable|in:MCCT,4PS,UCT',
            'family_group' => 'nullable|string|max:255',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|between:0,999.99',
            'weight' => 'nullable|numeric|between:0,999.99',
            'skin_complexion' => 'nullable|in:Fair,Medium,Light Brown,Brown,Black',
            'voter' => 'nullable|in:Yes,No',
            'resident_voter' => 'nullable|in:Yes,No',
            'year_last_voted' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $resident->update($request->all());

        return redirect()->route('residents.index')
            ->with('success', 'Resident updated successfully.');
    }

    /**
     * Remove the specified resident from storage.
     */
    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('residents.index')->with('success', 'Resident deleted successfully.');
    }

    public function allInfo(Request $request)
    {
        $query = Resident::query();
        
        // Apply filters if they exist
        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }
        
        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }
        
        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }
        
        if ($request->filled('civil_status')) {
            $query->where('civil_status', $request->civil_status);
        }
        
        if ($request->filled('age_min')) {
            $query->where('age', '>=', $request->age_min);
        }
        
        if ($request->filled('age_max')) {
            $query->where('age', '<=', $request->age_max);
        }
        
        if ($request->filled('voter')) {
            $query->where('voter', $request->voter);
        }
        
        if ($request->filled('education')) {
            $query->where('education', $request->education);
        }
        
        if ($request->filled('occupation')) {
            $query->where('occupation', 'like', '%' . $request->occupation . '%');
        }
        
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }
        
        if ($request->filled('program_participation')) {
            $query->where('program_participation', $request->program_participation);
        }
        
        if ($request->filled('labor_status')) {
            $query->where('labor_status', $request->labor_status);
        }
        
        if ($request->filled('family_group')) {
            $query->where('family_group', 'like', '%' . $request->family_group . '%');
        }
        
        if ($request->filled('household_id')) {
            $query->where('household_id', 'like', '%' . $request->household_id . '%');
        }
        
        // Order by last name
        $residents = $query->orderBy('last_name')->get();
        
        return view('residents.all_info', compact('residents'));
    }
} 