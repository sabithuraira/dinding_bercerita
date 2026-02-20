<?php

namespace App\Http\Controllers;

use App\Models\CurhatAnon;
use App\Models\KataMotivasi;
use App\Models\MusiUser;
use App\Models\SpadaAnswer;
use App\Models\SpadaQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        // Spada: show question active today (start_active <= today <= last_active);
        // if none, show the last active question (most recent last_active before today)
        $spadaActiveToday = null;
        $spadaActiveTodayAnswers = collect();
        $spadaWordCloud = collect(); // type_question=2: unique answer => count for word cloud
        if (class_exists(SpadaQuestion::class) && class_exists(SpadaAnswer::class)) {
            $today = now()->toDateString();
            $spadaActiveToday = SpadaQuestion::where('start_active', '<=', $today)
                ->where('last_active', '>=', $today)
                ->first();
            if (! $spadaActiveToday) {
                $spadaActiveToday = SpadaQuestion::where('last_active', '<', $today)
                    ->orderBy('last_active', 'desc')
                    ->first();
            }
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

    /**
     * Show form to submit SpadaAnswer for the active question today (start_active <= today <= last_active).
     */
    public function spadaForm(): View|RedirectResponse
    {
        if (! class_exists(SpadaQuestion::class)) {
            return redirect('/')->with('message', 'SPADA tidak tersedia.');
        }

        $today = now()->toDateString();
        $question = SpadaQuestion::where('start_active', '<=', $today)
            ->where('last_active', '>=', $today)
            ->first();

        if (! $question) {
            return redirect('/')->with('message', 'Tidak ada pertanyaan SPADA aktif untuk hari ini.');
        }

        $maxLength = $this->getSpadaAnswerMaxLength($question);

        return view('spada-form', [
            'question' => $question,
            'maxLength' => $maxLength,
        ]);
    }

    /**
     * Store SpadaAnswer from form. question_id must be the active question for today; status_approve = 2.
     */
    public function storeSpadaAnswer(Request $request): RedirectResponse
    {
        $request->validate([
            'question_id' => 'required|integer|exists:spada_question,id',
            'answer' => 'required|string|max:10000',
        ]);

        $today = now()->toDateString();
        $question = SpadaQuestion::where('id', $request->question_id)
            ->where('start_active', '<=', $today)
            ->where('last_active', '>=', $today)
            ->first();

        if (! $question) {
            return back()->withInput()->with('error', 'Pertanyaan tidak aktif atau tidak ditemukan.');
        }

        $maxLength = $this->getSpadaAnswerMaxLength($question);
        $request->validate(['answer' => 'required|string|max:' . $maxLength]);

        SpadaAnswer::create([
            'question_id' => $question->id,
            'answer' => $request->answer,
            'status_approve' => 2,
        ]);

        return redirect()->route('spada.form')->with('success', 'Jawaban berhasil dikirim. Terima kasih!');
    }

    /**
     * Max character length for answer input from SpadaQuestion:
     * - If validate_rule contains a number, use that.
     * - Else if type_question == 2, use 200.
     * - Else default 1000.
     */
    private function getSpadaAnswerMaxLength(SpadaQuestion $question): int
    {
        $rule = $question->validate_rule;
        if ($rule !== null && $rule !== '') {
            $decoded = json_decode($rule);
            if (is_object($decoded) || is_array($decoded)) {
                foreach ((array) $decoded as $v) {
                    if (is_numeric($v)) {
                        return (int) $v;
                    }
                }
            }
            if (preg_match('/\d+/', $rule, $m)) {
                return (int) $m[0];
            }
        }
        if ($question->type_question == 2) {
            return 200;
        }
        return 1000;
    }
}
