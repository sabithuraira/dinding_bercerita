<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MusiUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i:s'),
            'nip_baru' => $this->nip_baru,
            'urutreog' => $this->urutreog,
            'kdorg' => $this->kdorg,
            'nmorg' => $this->nmorg,
            'nmjab' => $this->nmjab,
            'flagwil' => $this->flagwil,
            'kdprop' => $this->kdprop,
            'kdkab' => $this->kdkab,
            'kdkec' => $this->kdkec,
            'nmwil' => $this->nmwil,
            'kdgol' => $this->kdgol,
            'nmgol' => $this->nmgol,
            'kdstjab' => $this->kdstjab,
            'nmstjab' => $this->nmstjab,
            'kdesl' => $this->kdesl,
            'foto' => $this->foto,
            'kode_desa' => $this->kode_desa,
            'pimpinan_id' => $this->pimpinan_id,
            'pimpinan_nik' => $this->pimpinan_nik,
            'pimpinan_nama' => $this->pimpinan_nama,
            'pimpinan_jabatan' => $this->pimpinan_jabatan,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
