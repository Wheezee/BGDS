<?php

namespace App\Providers;

use App\Models\Meeting;
use App\Models\FinancialRecord;
use App\Policies\MeetingPolicy;
use App\Policies\FinancialRecordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Policies\ProjectPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Meeting::class => MeetingPolicy::class,
        FinancialRecord::class => FinancialRecordPolicy::class,
        Project::class => ProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
} 