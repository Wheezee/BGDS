<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function view(User $user, Project $project)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function create(User $user)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function update(User $user, Project $project)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }

    public function delete(User $user, Project $project)
    {
        return in_array($user->role, ['superadmin', 'admin', 'barangay_chairman', 'barangay_secretary']);
    }
} 