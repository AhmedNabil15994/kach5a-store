<?php

namespace Modules\DeliveryTime\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
           'time_from'            => $this->from,
           'time_to'          => $this->to,
       ];
    }
}
