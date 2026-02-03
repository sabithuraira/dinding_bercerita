<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusiUser;
use App\Http\Resources\MusiUserResource;

class MusiUserController extends Controller
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

        $query = MusiUser::orderBy('created_at', 'desc');

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }
        if ($request->filled('kdprop')) {
            $query->where('kdprop', $request->get('kdprop'));
        }
        if ($request->filled('kdkab')) {
            $query->where('kdkab', $request->get('kdkab'));
        }

        $datas = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => '1',
            'datas' => MusiUserResource::collection($datas->items()),
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
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:musi_users,email',
            'password' => 'required|string|min:8',
            'nip_baru' => 'required|string|max:191',
            'nmjab' => 'required|string|max:191',
            'flagwil' => 'required|string|max:191',
            'kdprop' => 'required|string|size:3',
            'kdkab' => 'required|string|size:3',
            'kdkec' => 'required|string|max:191',
            'nmwil' => 'required|string|max:191',
            'kdgol' => 'required|string|max:191',
            'nmgol' => 'required|string|max:191',
            'kdstjab' => 'required|string|max:191',
            'nmstjab' => 'required|string|max:191',
            'kdesl' => 'required|string|max:191',
        ], [], [
            'nip_baru' => 'NIP',
            'nmjab' => 'Nama Jabatan',
            'flagwil' => 'Flag Wilayah',
            'kdprop' => 'Kode Provinsi',
            'kdkab' => 'Kode Kabupaten',
            'kdkec' => 'Kode Kecamatan',
            'nmwil' => 'Nama Wilayah',
            'kdgol' => 'Kode Golongan',
            'nmgol' => 'Nama Golongan',
            'kdstjab' => 'Kode Status Jabatan',
            'nmstjab' => 'Nama Status Jabatan',
            'kdesl' => 'Kode Eselon',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['urutreog'] = $request->get('urutreog');
        $validated['kdorg'] = $request->get('kdorg');
        $validated['nmorg'] = $request->get('nmorg');
        $validated['foto'] = $request->get('foto');
        $validated['kode_desa'] = $request->get('kode_desa');
        $validated['pimpinan_id'] = $request->get('pimpinan_id');
        $validated['pimpinan_nik'] = $request->get('pimpinan_nik');
        $validated['pimpinan_nama'] = $request->get('pimpinan_nama');
        $validated['pimpinan_jabatan'] = $request->get('pimpinan_jabatan');
        $validated['is_active'] = $request->get('is_active', 1);

        $model = MusiUser::create($validated);

        return response()->json([
            'success' => '1',
            'message' => 'Data berhasil disimpan',
            'data' => new MusiUserResource($model),
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
        $model = MusiUser::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => '1',
            'data' => new MusiUserResource($model),
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
        $model = MusiUser::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        $rules = [
            'name' => 'sometimes|string|max:191',
            'email' => 'sometimes|email|unique:musi_users,email,' . $id,
            'nip_baru' => 'sometimes|string|max:191',
            'nmjab' => 'sometimes|string|max:191',
            'flagwil' => 'sometimes|string|max:191',
            'kdprop' => 'sometimes|string|size:3',
            'kdkab' => 'sometimes|string|size:3',
            'kdkec' => 'sometimes|string|max:191',
            'nmwil' => 'sometimes|string|max:191',
            'kdgol' => 'sometimes|string|max:191',
            'nmgol' => 'sometimes|string|max:191',
            'kdstjab' => 'sometimes|string|max:191',
            'nmstjab' => 'sometimes|string|max:191',
            'kdesl' => 'sometimes|string|max:191',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }
        $validated = $request->validate($rules);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $fillable = [
            'name', 'email', 'email_verified_at', 'password', 'remember_token',
            'nip_baru', 'urutreog', 'kdorg', 'nmorg', 'nmjab', 'flagwil',
            'kdprop', 'kdkab', 'kdkec', 'nmwil', 'kdgol', 'nmgol',
            'kdstjab', 'nmstjab', 'kdesl', 'foto', 'kode_desa',
            'pimpinan_id', 'pimpinan_nik', 'pimpinan_nama', 'pimpinan_jabatan',
            'is_active',
        ];
        foreach ($fillable as $key) {
            if ($request->has($key)) {
                $model->$key = $request->get($key);
            }
        }
        if ($request->filled('password')) {
            $model->password = bcrypt($request->get('password'));
        }

        if ($model->save()) {
            return response()->json([
                'success' => '1',
                'message' => 'Data berhasil diupdate',
                'data' => new MusiUserResource($model->fresh()),
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

        $model = MusiUser::find($id);

        if ($model === null) {
            return response()->json(['success' => '0', 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($model->delete()) {
            return response()->json(['success' => '1', 'message' => 'Data berhasil dihapus']);
        }

        return response()->json(['success' => '0', 'message' => 'Gagal menghapus data']);
    }
}
