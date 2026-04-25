<?php

namespace App\Http\Controllers;

use App\Http\Resources\PromotingPictureResource;
use App\Models\PromotingPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotingPictureController extends Controller
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

        $query = PromotingPicture::orderBy('created_at', 'desc');

        $datas = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => '1',
            'datas' => PromotingPictureResource::collection($datas->items()),
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
            'title' => 'required|string|max:191',
            'picture' => 'required|file|image|max:5120',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [], [
            'title' => 'Title',
            'picture' => 'Picture',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ]);

        $picturePath = $request->file('picture')->store('promoting-picture', 'public');

        $model = PromotingPicture::create([
            'title' => $validated['title'],
            'picture_path' => $picturePath,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return response()->json([
            'success' => '1',
            'message' => 'Data berhasil disimpan',
            'data' => new PromotingPictureResource($model),
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
        $model = PromotingPicture::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => '1',
            'data' => new PromotingPictureResource($model),
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
        $model = PromotingPicture::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:191',
            'picture' => 'sometimes|file|image|max:5120',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'title' => 'Title',
            'picture' => 'Picture',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ]);

        $nextStartDate = $validated['start_date'] ?? $model->start_date?->format('Y-m-d');
        $nextEndDate = $validated['end_date'] ?? $model->end_date?->format('Y-m-d');
        if ($nextStartDate && $nextEndDate && $nextEndDate < $nextStartDate) {
            return response()->json([
                'success' => '0',
                'message' => 'End Date harus sama atau setelah Start Date',
            ], 422);
        }

        if ($request->has('title')) {
            $model->title = $request->get('title');
        }
        if ($request->has('start_date')) {
            $model->start_date = $request->get('start_date');
        }
        if ($request->has('end_date')) {
            $model->end_date = $request->get('end_date');
        }
        if ($request->hasFile('picture')) {
            if (! empty($model->picture_path) && Storage::disk('public')->exists($model->picture_path)) {
                Storage::disk('public')->delete($model->picture_path);
            }
            $model->picture_path = $request->file('picture')->store('promoting-picture', 'public');
        }

        if ($model->save()) {
            return response()->json([
                'success' => '1',
                'message' => 'Data berhasil diupdate',
                'data' => new PromotingPictureResource($model->fresh()),
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

        $model = PromotingPicture::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        if (! empty($model->picture_path) && Storage::disk('public')->exists($model->picture_path)) {
            Storage::disk('public')->delete($model->picture_path);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }
}
