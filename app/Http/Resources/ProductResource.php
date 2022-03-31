<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "category_id" => $this->category_id,
            "name" => $this->name,
            "description" => $this->description,
            "features" => $this->features,
            "attributes" => AttributeValueResource::collection($this->whenLoaded('attributeValue')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
