<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'recived' => $this->recived,
            'courses' => $this->Courses,
            'delete_supplier' => url("api/admin/delete-supplier/$this->id")
        ];
    }
}
