<?php

namespace App\Http\Controllers;

use App\Models\CurhatAnon;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the home page with random approved curhats.
     */
    public function index(): View
    {
        // Get last 24 approved curhats, then pick up to 12 random
        $approvedCurhats = CurhatAnon::where('status_verifikasi', 2)
            ->orderBy('created_at', 'desc')
            ->limit(24)
            ->get();

        // Get up to 12 random from the pool
        $randomCurhats = $approvedCurhats->shuffle()->take(12);

        return view('home', ['curhats' => $randomCurhats]);
    }
}
