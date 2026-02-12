<?php

namespace App\Http\Controllers;

use App\Models\CurhatAnon;
use App\Models\KataMotivasi;
use App\Models\MusiUser;
use App\Models\SpadaAnswer;
use App\Models\SpadaQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the home page with random approved curhats, birthday list, kata motivasi, and Spada active today.
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

        // Spada: active question today (same logic as /api/spada-question/active-today)
        $spadaActiveToday = null;
        $spadaActiveTodayAnswers = collect();
        $spadaWordCloud = collect(); // type_question=2: unique answer => count for word cloud
        if (class_exists(SpadaQuestion::class) && class_exists(SpadaAnswer::class)) {
            $today = now()->toDateString();
            $spadaActiveToday = SpadaQuestion::where('start_active', '<=', $today)
                ->where('last_active', '>=', $today)
                ->first();
            if ($spadaActiveToday) {
                $spadaActiveTodayAnswers = SpadaAnswer::where('question_id', $spadaActiveToday->id)
                    ->where('status_approve', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(1000)
                    ->get();
                // For word cloud (type_question=2): group by answer text, count occurrences
                if ($spadaActiveToday->type_question == 2 && $spadaActiveTodayAnswers->isNotEmpty()) {
                    $spadaWordCloud = $spadaActiveTodayAnswers->groupBy('answer')->map(function ($items, $answer) {
                        return (object) ['answer' => $answer, 'count' => $items->count()];
                    })->values();
                }
            }
        }

        return view('home', [
            'curhats' => $randomCurhats,
            'birthday' => $birthday,
            'kataMotivasi' => $kataMotivasi,
            'spadaActiveToday' => $spadaActiveToday,
            'spadaActiveTodayAnswers' => $spadaActiveTodayAnswers,
            'spadaWordCloud' => $spadaWordCloud,
        ]);
    }
}
