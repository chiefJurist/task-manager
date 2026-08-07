<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinutes(5, 5)->by($request->input('email') ?: $request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => __('auth.throttle', ['seconds' => $headers['Retry-After']])
                ], 429, $headers);
            });
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(3, 5)->by( $request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => __('auth.throttle', ['seconds' => $headers['Retry-After']])
                ], 429, $headers);
            });
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => __('auth.throttle', ['seconds' => $headers['Retry-After']])
                ], 429, $headers);
            });
        });
    }
}
