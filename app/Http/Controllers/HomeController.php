<?php

namespace App\Http\Controllers;

use App\Models\CurhatAnon;
use App\Models\KataMotivasi;
use App\Models\MusiUser;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the home page with random approved curhats, birthday list, and kata motivasi.
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

        // Get musi_users who have birthday today (NIP format: positions 5-6 = month, 7-8 = day)
        $birthday = collect();
        if (class_exists(MusiUser::class)) {
            $birthday = MusiUser::where(DB::raw('SUBSTRING(nip_baru, 5, 2)'), date('m'))
                ->where(DB::raw('SUBSTRING(nip_baru, 7, 2)'), date('d'))
                ->where('is_active', 1)
                ->get();
        }

        // Get one random kata_motivasi (is_active=1) for Slide 3
        $kataMotivasi = null;
        if (class_exists(KataMotivasi::class)) {
            $kataMotivasi = KataMotivasi::where('is_active', 1)->inRandomOrder()->first();
        }

        return view('home', [
            'curhats' => $randomCurhats,
            'birthday' => $birthday,
            'kataMotivasi' => $kataMotivasi,
        ]);
    }
}
