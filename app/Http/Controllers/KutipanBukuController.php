<?php

namespace App\Http\Controllers;

use App\Http\Resources\KutipanBukuResource;
use App\Models\KutipanBuku;
use Illuminate\Http\Request;

class KutipanBukuController extends Controller
{
    /**
     * Show single data where date_show is today. If not exists, return null.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToday()
    {
        $today = now()->toDateString();
        $model = KutipanBuku::whereDate('date_show', $today)->orderBy('id', 'desc')->first();

        return response()->json([
            'success' => '1',
            'data' => $model ? new KutipanBukuResource($model) : null,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page') ?? $request->get('p') ?? $request->get('page_num') ?? 1;

        $query = KutipanBuku::orderBy('created_at', 'desc');

        if ($request->filled('date_show')) {
            $query->whereDate('date_show', $request->get('date_show'));
        }

        $datas = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => '1',
            'datas' => KutipanBukuResource::collection($datas->items()),
            'pagination' => [
                'current_page' => $datas->currentPage(),
                'last_page' => $datas->lastPage(),
                'per_page' => $datas->perPage(),
                'total' => $datas->total(),
                'from' => $datas->firstItem(),
                'to' => $datas->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote' => 'required|string',
            'dikutip_dari' => 'nullable|string|max:191',
            'date_show' => 'nullable|date',
        ], [], [
            'quote' => 'Quote',
            'dikutip_dari' => 'Dikutip Dari',
            'date_show' => 'Date Show',
        ]);

        $model = KutipanBuku::create($validated);

        return response()->json([
            'success' => '1',
            'message' => 'Data berhasil disimpan',
            'data' => new KutipanBukuResource($model),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $model = KutipanBuku::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => '1',
            'data' => new KutipanBukuResource($model),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $model = KutipanBuku::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'quote' => 'sometimes|string',
            'dikutip_dari' => 'nullable|string|max:191',
            'date_show' => 'nullable|date',
        ], [], [
            'quote' => 'Quote',
            'dikutip_dari' => 'Dikutip Dari',
            'date_show' => 'Date Show',
        ]);

        if ($request->has('quote')) {
            $model->quote = $request->get('quote');
        }
        if ($request->has('dikutip_dari')) {
            $model->dikutip_dari = $request->get('dikutip_dari');
        }
        if ($request->has('date_show')) {
            $model->date_show = $request->get('date_show');
        }

        if ($model->save()) {
            return response()->json([
                'success' => '1',
                'message' => 'Data berhasil diupdate',
                'data' => new KutipanBukuResource($model->fresh()),
            ]);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal mengupdate data']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id = null)
    {
        $id = $id ?? $request->get('form_id_data') ?? $request->get('id');

        if ($id === null || $id === '' || $id === 0) {
            return response()->json(['success' => '0', 'message' => 'ID tidak valid']);
        }

        $model = KutipanBuku::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }
}
