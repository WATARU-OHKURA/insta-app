<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        FacadesGate::define('admin', function ($user) {
            return $user->role_id === User::ADMIN_ROLE_ID;
        });

        View::composer(['layouts.app'], function ($view) {
            $view->with('userCount', User::withTrashed()->count());
            $view->with('postCount', Post::withTrashed()->count());
            $view->with('categoryCount', Category::count());
        });
    }
}
