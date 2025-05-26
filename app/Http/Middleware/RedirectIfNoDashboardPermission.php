<?php

namespace App\Http\Middleware;

use App\Services\UserRedirectService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoDashboardPermission
{
    protected $redirectService;

    public function __construct(UserRedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->can('dashboard')) {
            return $next($request);
        }

        $routeName = $this->redirectService->getHomeRouteForUser($user);

        return redirect()->route($routeName);
    }
}
