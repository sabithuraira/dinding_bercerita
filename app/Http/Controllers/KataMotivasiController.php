<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KataMotivasi;
use App\Http\Resources\KataMotivasiResource;

class KataMotivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page') ?? $request->get('p') ?? $request->get('page_num') ?? 1;

        $query = KataMotivasi::orderBy('created_at', 'desc');

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        $datas = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => '1',
            'datas' => KataMotivasiResource::collection($datas->items()),
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
            'kata_motivasi' => 'required|string',
            'dikutip_dari' => 'nullable|string|max:191',
            'created_nip' => 'nullable|string|max:191',
            'is_active' => 'nullable|integer|in:0,1',
        ], [], [
            'kata_motivasi' => 'Kata Motivasi',
            'dikutip_dari' => 'Dikutip Dari',
            'created_nip' => 'Created NIP',
        ]);

        $validated['is_active'] = $request->get('is_active', 1);

        $model = KataMotivasi::create($validated);

        return response()->json([
            'success' => '1',
            'message' => 'Data berhasil disimpan',
            'data' => new KataMotivasiResource($model),
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
        $model = KataMotivasi::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => '1',
            'data' => new KataMotivasiResource($model),
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
        $model = KataMotivasi::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'kata_motivasi' => 'sometimes|string',
            'dikutip_dari' => 'nullable|string|max:191',
            'created_nip' => 'nullable|string|max:191',
            'is_active' => 'nullable|integer|in:0,1',
        ], [], [
            'kata_motivasi' => 'Kata Motivasi',
            'dikutip_dari' => 'Dikutip Dari',
            'created_nip' => 'Created NIP',
        ]);

        if ($request->has('kata_motivasi')) {
            $model->kata_motivasi = $request->get('kata_motivasi');
        }
        if ($request->has('dikutip_dari')) {
            $model->dikutip_dari = $request->get('dikutip_dari');
        }
        if ($request->has('created_nip')) {
            $model->created_nip = $request->get('created_nip');
        }
        if ($request->has('is_active')) {
            $model->is_active = (int) $request->get('is_active');
        }

        if ($model->save()) {
            return response()->json([
                'success' => '1',
                'message' => 'Data berhasil diupdate',
                'data' => new KataMotivasiResource($model->fresh()),
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

        $model = KataMotivasi::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }
}
