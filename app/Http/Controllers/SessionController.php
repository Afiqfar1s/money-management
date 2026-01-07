<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Display all sessions (admin only)
     */
    public function adminIndex()
    {
        // Get all sessions with user information
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'users.name as user_name',
                'users.email as user_email',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity'
            )
            ->orderBy('sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'user_id' => $session->user_id,
                    'user_name' => $session->user_name ?? 'Guest',
                    'user_email' => $session->user_email ?? 'N/A',
                    'ip_address' => $session->ip_address,
                    'user_agent' => $this->parseUserAgent($session->user_agent),
                    'last_activity' => date('Y-m-d H:i:s', $session->last_activity),
                    'is_current' => $session->id === session()->getId(),
                ];
            });

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Revoke a specific session
     */
    public function destroy($sessionId)
    {
        $currentSessionId = session()->getId();
        
        // Prevent admin from deleting their current session
        if ($sessionId === $currentSessionId) {
            return back()->with('error', 'You cannot revoke your current session. Please logout instead.');
        }

        // Admin can delete any session
        DB::table('sessions')->where('id', $sessionId)->delete();

        return back()->with('success', 'Session revoked successfully.');
    }

    /**
     * Parse user agent string to get device and browser info
     */
    private function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $platform = 'Unknown';

        // Detect browser
        if (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        }

        // Detect platform
        if (strpos($userAgent, 'Windows') !== false) {
            $platform = 'Windows';
        } elseif (strpos($userAgent, 'Macintosh') !== false || strpos($userAgent, 'Mac OS') !== false) {
            $platform = 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $platform = 'Linux';
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            $platform = 'iOS';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $platform = 'Android';
        }

        return "$browser on $platform";
    }
}
