<?php

namespace App\Providers;

use App\Models\TimeCapsule;
use App\Policies\TimeCapsulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        TimeCapsule::class => TimeCapsulePolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
