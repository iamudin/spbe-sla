<?php

namespace App\Http\Controllers\Plugins\SpbeSla\Middleware;

use Illuminate\Http\Request;
use Closure;

class RedirectMiddleware
{
    public static function handle()
    {
        return function (Request $request, Closure $next) {
            $customDomain = get_option('spbe-sla-domain');
            $host = $request->getHost();
            $path = $request->path();

            // Check if this is a short route (without spbe-sla/)
            $isShortPath = !preg_match('/^spbe-sla(\/|$)/', ltrim($path, '/'));

            if ($customDomain) {
                if ($host !== $customDomain) {
                    // Accessing from main domain but custom domain exists -> redirect to custom domain
                    $newPath = preg_replace('/^spbe-sla\/?/', '', ltrim($path, '/'));
                    $url = $request->getScheme() . '://' . $customDomain . '/' . ltrim($newPath, '/');
                    if ($request->getQueryString()) {
                        $url .= '?' . $request->getQueryString();
                    }
                    return redirect()->to($url);
                } else {
                    // On custom domain but using long route -> redirect to short route
                    if (!$isShortPath) {
                        $newPath = preg_replace('/^spbe-sla\/?/', '', ltrim($path, '/'));
                        $url = $request->getScheme() . '://' . $customDomain . '/' . ltrim($newPath, '/');
                        if ($request->getQueryString()) {
                            $url .= '?' . $request->getQueryString();
                        }
                        return redirect()->to($url);
                    }
                }
            } else {
                // If NO custom domain is set, MUST use long route (spbe-sla/...)
                if ($isShortPath) {
                    abort(404);
                }

                // If accessed from a foreign host (e.g. former custom domain) while no custom domain is set,
                // redirect back to the main domain/active tenant.
                $activeDomain = config('modules.multisite_enabled') && function_exists('tenant') && tenant()
                    ? tenant()->domain
                    : parse_url(config('app.url'), PHP_URL_HOST);

                if ($host !== $activeDomain && $activeDomain) {
                    $url = $request->getScheme() . '://' . $activeDomain . '/' . ltrim($path, '/');
                    if ($request->getQueryString()) {
                        $url .= '?' . $request->getQueryString();
                    }
                    return redirect()->to($url);
                }
            }
            
            return $next($request);
        };
    }
}
