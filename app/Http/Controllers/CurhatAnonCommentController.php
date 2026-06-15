<?php

namespace App\Http\Controllers;

use App\Models\CurhatAnon;
use App\Models\CurhatAnonComment;
use App\Rules\Recaptcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurhatAnonCommentController extends Controller
{
    /**
     * List anonymous comments for an approved curhat.
     */
    public function index(int $curhatAnonId): JsonResponse
    {
        $curhat = CurhatAnon::where('id', $curhatAnonId)
            ->where('status_verifikasi', 2)
            ->first();

        if ($curhat === null) {
            return response()->json([
                'success' => '0',
                'message' => 'Curhat tidak ditemukan atau belum disetujui',
            ], 404);
        }

        $comments = CurhatAnonComment::where('curhat_anon_id', $curhatAnonId)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'comment', 'created_at']);

        return response()->json([
            'success' => '1',
            'comments' => $comments->map(fn (CurhatAnonComment $comment) => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at?->format('Y-m-d H:i'),
            ]),
            'comments_count' => $comments->count(),
        ]);
    }

    /**
     * Store an anonymous comment for an approved curhat.
     */
    public function store(Request $request, int $curhatAnonId): JsonResponse
    {
        $curhat = CurhatAnon::where('id', $curhatAnonId)
            ->where('status_verifikasi', 2)
            ->first();

        if ($curhat === null) {
            return response()->json([
                'success' => '0',
                'message' => 'Curhat tidak ditemukan atau belum disetujui',
            ], 404);
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
            'g-recaptcha-response' => ['required', new Recaptcha()],
        ], [], [
            'comment' => 'Komentar',
            'g-recaptcha-response' => 'reCAPTCHA',
        ]);

        CurhatAnonComment::create([
            'curhat_anon_id' => $curhatAnonId,
            'comment' => $validated['comment'],
        ]);

        $commentsCount = $curhat->comments()->count();

        return response()->json([
            'success' => '1',
            'message' => 'Komentar berhasil dikirim',
            'comments_count' => $commentsCount,
        ]);
    }
}
