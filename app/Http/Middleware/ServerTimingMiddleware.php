<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServerTimingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $initialQueryCount = count(DB::getQueryLog());
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000;
        $queryCount = count(DB::getQueryLog()) - $initialQueryCount;
        $totalQueryTime = 0;

        foreach (DB::getQueryLog() as $query) {
            $totalQueryTime += $query['time'];
        }

        // Only add headers to responses that support the header() method
        // BinaryFileResponse doesn't have header() method
        if (method_exists($response, 'header')) {
            $response->header('X-Query-Count', $queryCount);
            $response->header('X-Query-Time', round($totalQueryTime, 2) . ' ms');
            $response->header('X-Total-Time', round($totalTime, 2) . ' ms');

            $timingHeader = sprintf(
                'db;dur=%d;desc="Database Queries (%d queries)", total;dur=%d;desc="Total Time"',
                (int)$totalQueryTime,
                $queryCount,
                (int)$totalTime
            );
            $response->header('Server-Timing', $timingHeader);
        }

        Log::debug('Request Performance', [
            'path' => $request->path(),
            'method' => $request->method(),
            'query_count' => $queryCount,
            'total_query_time_ms' => round($totalQueryTime, 2),
            'total_time_ms' => round($totalTime, 2),
        ]);

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
