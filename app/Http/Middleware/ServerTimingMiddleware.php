<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServerTimingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Record DB queries if query logging is enabled
        $initialQueryCount = count(DB::getQueryLog());
        $startTime = microtime(true);

        // Process the request
        $response = $next($request);

        // Calculate metrics
        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $queryCount = count(DB::getQueryLog()) - $initialQueryCount;
        $totalQueryTime = 0;

        // Calculate total query time from logs
        foreach (DB::getQueryLog() as $query) {
            $totalQueryTime += $query['time'];
        }

        // Add custom headers
        $response->header('X-Query-Count', $queryCount);
        $response->header('X-Query-Time', round($totalQueryTime, 2) . ' ms');
        $response->header('X-Total-Time', round($totalTime, 2) . ' ms');

        // Add Server-Timing header for DevTools
        $timingHeader = sprintf(
            'db;dur=%d;desc="Database Queries (%d queries)", total;dur=%d;desc="Total Time"',
            (int)$totalQueryTime,
            $queryCount,
            (int)$totalTime
        );
        $response->header('Server-Timing', $timingHeader);

        // Log performance metrics
        Log::debug('Request Performance', [
            'path' => $request->path(),
            'method' => $request->method(),
            'query_count' => $queryCount,
            'total_query_time_ms' => round($totalQueryTime, 2),
            'total_time_ms' => round($totalTime, 2),
        ]);

        // Warn if page load is slow (> 2 seconds)
        if ($totalTime > 2000) {
            Log::warning('Slow Page Detection', [
                'path' => $request->path(),
                'method' => $request->method(),
                'total_time_ms' => round($totalTime, 2),
                'query_count' => $queryCount,
                'threshold_ms' => 2000,
            ]);
        }

        return $response;
    }
}
