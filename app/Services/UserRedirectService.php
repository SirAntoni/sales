<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class UserRedirectService
{
    public function getHomeRouteForUser($user)
    {
        $modules = [
            'dashboard' => 'dashboard',
            'sales' => 'sales.index',
            'purchases' => 'purchases.index',
            'articles' => 'articles.index',
            'brands' => 'brands.index',
            'categories' => 'categories.index',
            'providers' => 'providers.index',
            'reports' => 'reports.index',
            'users' => 'users.index',
            'settings' => 'settings.index',
            'kardex' => 'kardex.index',
        ];

        foreach ($modules as $permission => $route) {
            if ($user->can($permission) && Route::has($route)) {
                return $route;
            }
        }

        return 'dashboard';
    }
}
