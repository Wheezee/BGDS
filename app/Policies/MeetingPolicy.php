<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    public function view(User $user, Meeting $meeting)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function create(User $user)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function update(User $user, Meeting $meeting)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function delete(User $user, Meeting $meeting)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }
} 