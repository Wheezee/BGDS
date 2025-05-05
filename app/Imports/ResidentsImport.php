<?php

namespace App\Imports;

use App\Models\Resident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ResidentsImport
{
    public function import($file)
    {
        // Map user-friendly headers to database fields
        $headerMap = [
            'Last Name' => 'last_name',
            'First Name' => 'first_name',
            'Middle Name' => 'middle_name',
            'Suffix' => 'suffix',
            'Place of Birth' => 'place_of_birth',
            'Date of Birth' => 'date_of_birth',
            'Age' => null,
            'Sex' => 'sex',
            'Civil Status' => 'civil_status',
            'Citizenship' => 'citizenship',
            'Occupation' => 'occupation',
            'Labor Status' => 'labor_status',
            'Contact Number' => 'contact_number',
            'Email' => 'email',
            'Education' => 'education',
            "Mother's Name" => 'mother_name',
            "Father's Name" => 'father_name',
            'PhilSys Card #' => 'philsys_number',
            'Household ID #' => 'household_id',
            'Program Participation' => 'program_participation',
            'Family Group' => 'family_group',
            'Blood Type' => 'blood_type',
            'Height' => 'height',
            'Weight' => 'weight',
            'Skin Complexion' => 'skin_complexion',
            'Voter' => 'voter',
            'Resident Voter' => 'resident_voter',
            'Year Last Voted' => 'year_last_voted',
            'Created At' => null,
            'Updated At' => null,
        ];

        try {
            Log::info('Starting import process');
            DB::beginTransaction();
            $reader = ReaderEntityFactory::createXLSXReader();
            Log::info('Opening file: ' . $file->getPathname());
            $reader->open($file->getPathname());
            $isFirstRow = true;
            $dbHeaders = [];
            $rowCount = 0;
            foreach ($reader->getSheetIterator() as $sheet) {
                Log::info('Processing sheet');
                foreach ($sheet->getRowIterator() as $row) {
                    $values = $row->toArray();
                    Log::info('Row values: ' . json_encode($values));
                    if ($isFirstRow) {
                        // Map headers
                        foreach ($values as $header) {
                            $dbHeaders[] = $headerMap[$header] ?? null;
                        }
                        Log::info('Mapped headers: ' . json_encode($dbHeaders));
                        $isFirstRow = false;
                        continue;
                    }
                    // Build data array for DB fields only
                    $data = [];
                    foreach ($dbHeaders as $i => $dbField) {
                        if ($dbField) {
                            $data[$dbField] = $values[$i] ?? null;
                        }
                    }
                    // Skip rows missing required fields
                    if (empty($data['last_name']) || empty($data['first_name'])) {
                        Log::warning('Skipping row due to missing required fields: ' . json_encode($data));
                        continue;
                    }
                    // Parse date_of_birth if present
                    if (!empty($data['date_of_birth'])) {
                        try {
                            $dob = \Carbon\Carbon::parse($data['date_of_birth']);
                            $data['date_of_birth'] = $dob;
                            $data['age'] = $dob->age;
                        } catch (\Exception $e) {
                            $data['date_of_birth'] = null;
                            $data['age'] = null;
                        }
                    } else {
                        $data['age'] = null;
                    }
                    // Validate program_participation
                    $allowedPrograms = ['MCCT', '4PS', 'UCT'];
                    if (empty($data['program_participation']) || !in_array($data['program_participation'], $allowedPrograms)) {
                        $data['program_participation'] = null;
                    }
                    // Validate blood_type
                    $allowedBloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                    if (empty($data['blood_type']) || !in_array($data['blood_type'], $allowedBloodTypes)) {
                        $data['blood_type'] = null;
                    }
                    // Validate enumerated/constraint columns
                    $allowedLaborStatus = ['Employed', 'Unemployed', 'PWD', 'OFW', 'Solo Parent', 'OSY'];
                    if (empty($data['labor_status']) || !in_array($data['labor_status'], $allowedLaborStatus)) {
                        $data['labor_status'] = null;
                    }
                    $allowedSkinComplexion = ['Fair', 'Medium', 'Light Brown', 'Brown', 'Black'];
                    if (empty($data['skin_complexion']) || !in_array($data['skin_complexion'], $allowedSkinComplexion)) {
                        $data['skin_complexion'] = null;
                    }
                    $allowedVoter = ['Yes', 'No'];
                    if (empty($data['voter']) || !in_array($data['voter'], $allowedVoter)) {
                        $data['voter'] = null;
                    }
                    if (empty($data['resident_voter']) || !in_array($data['resident_voter'], $allowedVoter)) {
                        $data['resident_voter'] = null;
                    }
                    $allowedCivilStatus = ['Single', 'Married', 'Widowed', 'Divorced'];
                    if (empty($data['civil_status']) || !in_array($data['civil_status'], $allowedCivilStatus)) {
                        $data['civil_status'] = null;
                    }
                    // Robustly validate height and weight as decimals
                    foreach (['height', 'weight'] as $field) {
                        $val = isset($data[$field]) ? trim((string)$data[$field]) : null;
                        if ($val === '' || strtolower($val) === 'n/a' || $val === '?' || !is_numeric($val)) {
                            $data[$field] = null;
                        }
                    }
                    try {
                        $resident = Resident::create($data);
                        Log::info('Created resident with ID: ' . $resident->id);
                        $rowCount++;
                    } catch (\Exception $e) {
                        Log::error('Error creating resident: ' . $e->getMessage());
                        throw $e;
                    }
                }
            }
            $reader->close();
            DB::commit();
            Log::info('Import completed successfully. Imported ' . $rowCount . ' rows.');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($reader)) {
                $reader->close();
            }
            Log::error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }
} 