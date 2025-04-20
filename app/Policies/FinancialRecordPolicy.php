<?php

namespace App\Policies;

use App\Models\FinancialRecord;
use App\Models\User;

class FinancialRecordPolicy
{
    public function view(User $user, FinancialRecord $finance)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary', 'barangay_treasurer']);
    }

    public function create(User $user)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary', 'barangay_treasurer']);
    }

    public function update(User $user, FinancialRecord $finance)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary', 'barangay_treasurer']);
    }

    public function delete(User $user, FinancialRecord $finance)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary', 'barangay_treasurer']);
    }
} 