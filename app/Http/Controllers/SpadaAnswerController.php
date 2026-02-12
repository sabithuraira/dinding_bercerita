<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpadaAnswer;
use App\Models\SpadaQuestion;
use App\Http\Resources\SpadaAnswerResource;

class SpadaAnswerController extends Controller
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
        $filterQuestionId = $request->get('filter_question_id');
        $filterStatusApprove = $request->get('filter_status_approve');
        $withQuestion = $request->get('with_question', false);
        
        $query = SpadaAnswer::orderBy('created_at', 'desc');
        
        // Apply filter if provided
        if ($filterQuestionId && $filterQuestionId !== '') {
            $query->where('question_id', $filterQuestionId);
        }
        
        if ($filterStatusApprove !== null && $filterStatusApprove !== '') {
            $query->where('status_approve', $filterStatusApprove);
        }
        
        // Load question if requested
        if ($withQuestion) {
            $query->with('question');
        }
        
        $datas = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => '1', 
            'datas' => SpadaAnswerResource::collection($datas->items()),
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
        // Validate question_id exists
        $questionId =  $request->question_id;
        if ($questionId) {
            $question = SpadaQuestion::find($questionId);
            if ($question == null) {
                return response()->json(['success' => '0', 'message' => 'Question tidak ditemukan']);
            }
        }
        
        $model = new SpadaAnswer();
        
        // Check if updating existing record
        if ($request->form_id_data != 0 && $request->form_id_data != '') {
            $model = SpadaAnswer::find($request->form_id_data);
            if ($model == null) {
                return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
            }
        }

        $model->question_id = $questionId;
        $model->answer = $request->answer;
        $model->status_approve = (int) $request->status_approve ?? 2;
        
        if ($model->save()) {
            return response()->json([
                'success' => '1', 
                'message' => 'Data berhasil disimpan',
                'data' => new SpadaAnswerResource($model->load('question'))
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
        $model = SpadaAnswer::with('question')->find($id);
        
        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        return response()->json([
            'success' => '1',
            'data' => new SpadaAnswerResource($model)
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
        $model = SpadaAnswer::find($id);
        
        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        // Validate question_id if provided
        $questionId = $request->form_question_id ?? $request->question_id;
        if ($questionId && $questionId != $model->question_id) {
            $question = SpadaQuestion::find($questionId);
            if ($question == null) {
                return response()->json(['success' => '0', 'message' => 'Question tidak ditemukan']);
            }
        }

        $model->question_id = $questionId ?? $model->question_id;
        $model->answer = $request->form_answer ?? $request->answer ?? $model->answer;
        if ($request->has('status_approve') || $request->has('form_status_approve')) {
            $model->status_approve = (int) ($request->get('status_approve') ?? $request->get('form_status_approve') ?? $model->status_approve);
        }
        
        if ($model->save()) {
            return response()->json([
                'success' => '1', 
                'message' => 'Data berhasil diupdate',
                'data' => new SpadaAnswerResource($model->load('question'))
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

        $model = SpadaAnswer::find($id);

        if ($model == null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan']);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }

    /**
     * Get answers by question ID
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $questionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByQuestion(Request $request, $questionId)
    {
        $question = SpadaQuestion::find($questionId);
        
        if ($question == null) {
            return response()->json(['success' => '0', 'message' => 'Question tidak ditemukan']);
        }

        $query = SpadaAnswer::where('question_id', $questionId)->orderBy('created_at', 'desc');
        if ($request->has('status_approve')) {
            $query->where('status_approve', (int) $request->get('status_approve'));
        }
        $answers = $query->get();

        return response()->json([
            'success' => '1',
            'datas' => SpadaAnswerResource::collection($answers)
        ]);
    }

    /**
     * Update status_approve to 1 or 2 for one or multiple answers (bulk).
     * Body: status_approve (1 or 2), ids (array of id) or id (single id).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusApprove(Request $request)
    {
        $status = (int) ($request->get('status_approve') ?? $request->get('form_status_approve'));
        if (! in_array($status, [1, 2], true)) {
            return response()->json([
                'success' => '0',
                'message' => 'status_approve harus 1 atau 2.',
            ], 422);
        }

        $ids = $request->get('ids') ?? $request->get('form_ids');
        if (is_array($ids)) {
            $idList = array_map('intval', array_filter($ids));
        } else {
            $singleId = $request->get('id') ?? $request->get('form_id_data');
            $idList = $singleId !== null && $singleId !== '' ? [(int) $singleId] : [];
        }

        if (empty($idList)) {
            return response()->json([
                'success' => '0',
                'message' => 'Parameter ids atau id wajib diisi.',
            ], 422);
        }

        $updated = SpadaAnswer::whereIn('id', $idList)->update(['status_approve' => $status]);

        return response()->json([
            'success' => '1',
            'message' => 'Status berhasil diupdate.',
            'updated_count' => $updated,
        ]);
    }
}
