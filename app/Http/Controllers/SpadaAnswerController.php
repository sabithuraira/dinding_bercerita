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
        $withQuestion = $request->get('with_question', false);
        
        $query = SpadaAnswer::orderBy('created_at', 'desc');
        
        // Apply filter if provided
        if ($filterQuestionId && $filterQuestionId !== '') {
            $query->where('question_id', $filterQuestionId);
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
        $questionId = $request->form_question_id ?? $request->question_id;
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

        $model->question_id = $questionId ?? $request->form_question_id ?? $request->question_id;
        $model->answer = $request->form_answer ?? $request->answer;
        
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
    public function getByQuestion($questionId)
    {
        $question = SpadaQuestion::find($questionId);
        
        if ($question == null) {
            return response()->json(['success' => '0', 'message' => 'Question tidak ditemukan']);
        }

        $answers = SpadaAnswer::where('question_id', $questionId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => '1',
            'datas' => SpadaAnswerResource::collection($answers)
        ]);
    }
}
