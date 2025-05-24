<?php

namespace Modules\DeliveryTime\Entities;

use Illuminate\Database\Eloquent\Model;

class DeliveryTimes extends Model
{
   protected $guarded = [];


    public function times()
    {
        return $this->hasMany(DeliveryTimesAvailability::class, 'delivery_time_id');
    }
}
