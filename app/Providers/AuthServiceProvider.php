<?php

namespace App\Providers;

use App\Models\Meeting;
use App\Models\FinancialRecord;
use App\Policies\MeetingPolicy;
use App\Policies\FinancialRecordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Meeting::class => MeetingPolicy::class,
        FinancialRecord::class => FinancialRecordPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 