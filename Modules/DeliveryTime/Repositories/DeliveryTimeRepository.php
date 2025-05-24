<?php

namespace Modules\DeliveryTime\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Modules\DeliveryTime\Entities\DeliveryTimes;
use Modules\DeliveryTime\Entities\DeliveryTimesAvailability;

class DeliveryTimeRepository
{
    protected $delivery_times;

    function __construct(DeliveryTimes $delivery_times)
    {
        $this->delivery_times = $delivery_times;
    }

    public function getByDay($day)
    {
         return $this->delivery_times->where('day', $day)->with('times')->first();
    }

    public function getAll()
    {
         return $this->delivery_times->get();
    }
    /**
     * @throws \Exception
     */
    public function createOrUpdate($request)
    {

        DB::beginTransaction();

        try {

            foreach ($request->availability as $key => $value) {
               $delivery_times =  $this->delivery_times->updateOrCreate(['day' => $key], [
                    'day' => $key,
                    'status' => isset($value['status']) ? ($value['status'] ? true : false) : false,
                    'time_from' => $value['time_from'],
                    'time_to' => $value['time_to'],
                ]);

               if ($delivery_times) {
                   $time_slots = $this->getTimeSlot(120, $delivery_times->time_from, $delivery_times->time_to);
                   DeliveryTimesAvailability::query()->where('delivery_time_id', $delivery_times->id)->delete();
                   foreach ($time_slots as $slot) {
                       $new_slot = collect([
                           'delivery_time_id' =>  $delivery_times->id,
                           'from' =>  date('h:i A', strtotime($slot['slot_start_time'])),
                           'to' =>  date('h:i A', strtotime($slot['slot_end_time']))
                       ]);
                       DeliveryTimesAvailability::query()->create($new_slot->toArray());
                   }
               }
            }
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function getTimeSlot($interval, $start_time, $end_time)
    {
        $startPeriod = Carbon::parse($start_time);
        $endPeriod   = Carbon::parse($end_time);

        $period = CarbonPeriod::create($startPeriod, '2 hour', $endPeriod);
        $times  = [];

        foreach ($period as $key => $date) {
            $start = $date->format('H:i');
            $end = Carbon::parse($date->format('H:i'))->addMinutes($interval)->format('H:i');
            if (Carbon::parse($date->format('H:i'))->addMinutes($interval) <= Carbon::parse($end_time)) {
                $times[$key]['slot_start_time'] = $start;
                $times[$key]['slot_end_time'] = $end;
            } elseif (Carbon::parse($date->format('H:i'))->addMinutes($interval)->gt(Carbon::parse($end_time))) {
                $times[$key]['slot_start_time'] = $start;
                $times[$key]['slot_end_time'] = Carbon::parse($end_time)->format('H:i');
            }
        }

        return $times;
    }

}
