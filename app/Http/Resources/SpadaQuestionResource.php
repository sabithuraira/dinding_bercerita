<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpadaQuestionResource extends JsonResource
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
            'question' => $this->question,
            'type_question' => $this->type_question,
            'start_active' => $this->start_active?->format('Y-m-d'),
            'last_active' => $this->last_active?->format('Y-m-d'),
            'validate_rule' => $this->validate_rule,
            'satker' => $this->satker,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'answers_count' => $this->whenLoaded('answers', fn() => $this->answers->count()),
            'answers' => SpadaAnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
