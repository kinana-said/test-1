<?php

namespace App\Providers;

use App\Models\Portfolio;
use App\Models\User;
use App\Models\Section;
use App\Models\Contact;
use App\Models\Work;
use App\Policies\PortfolioPolicy;
use App\Policies\SectionPolicy;
use App\Policies\ContactPolicy;
use App\Policies\WorkPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class OwnerServiceProvider extends ServiceProvider
{
    protected $policies = [
        Portfolio::class => PortfolioPolicy::class,
        Section::class => SectionPolicy::class,
        Contact::class => ContactPolicy::class,
        Work::class => WorkPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
