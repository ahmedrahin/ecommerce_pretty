<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class TrackVisitors
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip(); 
        $agent = $request->header('User-Agent'); 

        // Parse device and browser info
        $browser = $this->getBrowser($agent);
        $device = $this->getDevice($agent);

        // Check if the visitor already exists today
        $visitorExists = DB::table('visitors')
            ->where('ip_address', $ip)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if (!$visitorExists) {
            // Save visitor to the database
            DB::table('visitors')->insert([
                'ip_address' => $ip,
                'browser' => $browser,
                'device' => $device,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }

    // Helper to parse browser
    private function getBrowser($agent)
    {
        if (strpos($agent, 'Chrome') !== false) return 'Chrome';
        if (strpos($agent, 'Firefox') !== false) return 'Firefox';
        if (strpos($agent, 'Safari') !== false) return 'Safari';
        if (strpos($agent, 'Edge') !== false) return 'Edge';
        if (strpos($agent, 'MSIE') !== false || strpos($agent, 'Trident') !== false) return 'Internet Explorer';
        return 'Unknown';
    }

    // Helper to parse device type
    private function getDevice($agent)
    {
        if (strpos($agent, 'Mobile') !== false) return 'Mobile';
        if (strpos($agent, 'Tablet') !== false) return 'Tablet';
        return 'Desktop';
    }
}
