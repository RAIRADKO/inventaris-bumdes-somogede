<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define Gates for authorization
        Gate::define('manageUsers', function (User $user) {
            return $user->canManageUsers();
        });

        Gate::define('approve', function (User $user) {
            return $user->canApprove();
        });

        Gate::define('viewAllData', function (User $user) {
            return $user->canViewAllData();
        });

        Gate::define('manageJournals', function (User $user) {
            return $user->canManageJournals();
        });

        Gate::define('editTransactions', function (User $user) {
            return $user->canEditTransactions();
        });
    }
}
