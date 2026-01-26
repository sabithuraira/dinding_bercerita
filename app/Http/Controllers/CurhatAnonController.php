<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurhatAnon;

class CurhatAnonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $filterStatus = $request->get('filter_status_verifikasi');
        
        $query = CurhatAnon::orderBy('created_at', 'desc');
        
        // Apply filter if provided
        if ($filterStatus && $filterStatus !== '') {
            $query->where('status_verifikasi', $filterStatus);
        }
        
        $datas = $query->paginate($perPage);

        return response()->json([
            'success' => '1', 
            'datas' => $datas->items(),
            'pagination' => [
                'current_page' => $datas->currentPage(),
                'last_page' => $datas->lastPage(),
                'per_page' => $datas->perPage(),
                'total' => $datas->total(),
                'from' => $datas->firstItem(),
                'to' => $datas->lastItem(),
            ]
        ]);
    }

    /**
     * Load data for AJAX requests (alias for index)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadData(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $model = new CurhatAnon();
        
        // Check if updating existing record
        if ($request->form_id_data != 0 && $request->form_id_data != '') {
            $model = CurhatAnon::find($request->form_id_data);
            if ($model == null) {
                return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
            }
        }

        $model->content = $request->form_content;
        $model->status_verifikasi = $request->form_status_verifikasi ?? 1;
        
        if ($model->save()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil disimpan']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menyimpan data']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $model = CurhatAnon::find($id);
        
        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        return response()->json([
            'success' => '1',
            'data' => $model
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
        $model = CurhatAnon::find($id);
        
        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        $model->content = $request->form_content ?? $request->content ?? $model->content;
        $model->status_verifikasi = $request->form_status_verifikasi ?? $request->status_verifikasi ?? $model->status_verifikasi;
        
        if ($model->save()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil diupdate']);
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
        $id = $id ?? $request->form_id_data ?? $request->id;
        
        if ($id == 0 || $id == '') {
            return response()->json(['success' => '0', 'message' => 'ID tidak valid']);
        }

        $model = CurhatAnon::find($id);

        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }

    /**
     * Get approved curhats for public display
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprovedCurhats(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $datas = CurhatAnon::where('status_verifikasi', 2) // Disetujui
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => '1', 
            'datas' => $datas
        ]);
    }
}
