<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpadaQuestion;
use App\Http\Resources\SpadaQuestionResource;

class SpadaQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page') ?? $request->get('p') ?? $request->get('page_num') ?? 1;
        $filterSatker = $request->get('filter_satker');
        $filterTypeQuestion = $request->get('filter_type_question');
        $withAnswers = $request->get('with_answers', false);
        
        $query = SpadaQuestion::orderBy('id', 'desc');
        
        // Apply filters if provided
        if ($filterSatker && $filterSatker !== '') {
            $query->where('satker', $filterSatker);
        }
        
        if ($filterTypeQuestion && $filterTypeQuestion !== '') {
            $query->where('type_question', $filterTypeQuestion);
        }
        
        // Load answers if requested
        if ($withAnswers) {
            $query->with('answers');
        }
        
        $datas = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => '1', 
            'datas' => SpadaQuestionResource::collection($datas->items()),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $model = new SpadaQuestion();
        
        // Check if updating existing record
        if ($request->form_id_data != 0 && $request->form_id_data != '') {
            $model = SpadaQuestion::find($request->form_id_data);
            if ($model == null) {
                return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
            }
        }

        $model->question = $request->form_question ?? $request->question;
        $model->type_question = $request->form_type_question ?? $request->type_question;
        $model->start_active = $request->form_start_active ?? $request->start_active;
        $model->last_active = $request->form_last_active ?? $request->last_active;
        $model->validate_rule = $request->form_validate_rule ?? $request->validate_rule;
        $model->satker = $request->form_satker ?? $request->satker;
        
        if ($model->save()) {
            return response()->json([
                'success' => '1', 
                'message' => 'Data berhasil disimpan',
                'data' => new SpadaQuestionResource($model)
            ]);
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
        $model = SpadaQuestion::with('answers')->find($id);
        
        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        return response()->json([
            'success' => '1',
            'data' => new SpadaQuestionResource($model)
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
        $model = SpadaQuestion::find($id);
        
        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        $model->question = $request->form_question ?? $request->question ?? $model->question;
        $model->type_question = $request->form_type_question ?? $request->type_question ?? $model->type_question;
        $model->start_active = $request->form_start_active ?? $request->start_active ?? $model->start_active;
        $model->last_active = $request->form_last_active ?? $request->last_active ?? $model->last_active;
        $model->validate_rule = $request->form_validate_rule ?? $request->validate_rule ?? $model->validate_rule;
        $model->satker = $request->form_satker ?? $request->satker ?? $model->satker;
        
        if ($model->save()) {
            return response()->json([
                'success' => '1', 
                'message' => 'Data berhasil diupdate',
                'data' => new SpadaQuestionResource($model)
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
        $id = $id ?? $request->form_id_data ?? $request->id;
        
        if ($id == 0 || $id == '') {
            return response()->json(['success' => '0', 'message' => 'ID tidak valid']);
        }

        $model = SpadaQuestion::find($id);

        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }

    /**
     * Get one SpadaQuestion that is active today.
     * Active means: start_active <= today AND last_active >= today.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveToday(Request $request)
    {
        $today = now()->toDateString();

        $model = SpadaQuestion::where('start_active', '<=', $today)
            ->where('last_active', '>=', $today)
            ->with('answers')
            ->first();

        if ($model === null) {
            return response()->json([
                'success' => '0',
                'message' => 'Tidak ada pertanyaan yang aktif untuk hari ini.',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => '1',
            'data' => new SpadaQuestionResource($model),
        ]);
    }
}