<?php

namespace Modules\DeliveryTime\Http\Controllers\WebService;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\DeliveryTime\Repositories\DeliveryTimeRepository;
use Modules\DeliveryTime\Transformers\WebService\DeliveryTimeResource;

class DeliveryTimeController extends WebServiceController
{

    protected $delivery_times;

    function __construct(DeliveryTimeRepository $delivery_times)
    {
        $this->delivery_times = $delivery_times;
    }

    public function getWorkingTimes(Request $request)
    {
        try {
            $buildDays = [];
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate = Carbon::today()->addDays(6)->format('Y-m-d');
                $period = CarbonPeriod::create($startDate, $endDate);

                foreach ($period as $index => $date) {
                    $shortDay = Str::lower($date->format('D'));

                    $day = $this->delivery_times->getByDay($shortDay);
                    if ($day) {
                        $times = [];
                        foreach ($day->times as $k => $time) {
                            if (now()->format('H:i') < Carbon::createFromFormat('H:i A', $time['to'])->format('H:i')) {
                                $times[] = $time;
                            }
                        }

                        if (count($times) > 0) {
                            $customTime = [
                                'date' => $date->format('Y-m-d'),
                                'day_code' => $shortDay,
                                'day_name' => __('company::dashboard.companies.availabilities.days.' . $shortDay),
                                'times' => DeliveryTimeResource::collection($times)
                            ];
                            $buildDays[] = $customTime;
                        } else {
                            $customTime = [
                                'date' => $date->format('Y-m-d'),
                                'day_code' => $shortDay,
                                'day_name' => __('company::dashboard.companies.availabilities.days.' . $shortDay),
                                'times' => DeliveryTimeResource::collection($day->times)
                            ];
                            $buildDays[] = $customTime;
                        }

                    }
                }
            return $this->response($buildDays);
        } catch (\Exception $e){
            return $this->error(__('deliverytime::webservice.something_went_wrong'));
        }
    }

    public function getDeliveryTimes(Request $request)
    {
        $userToken = $request->user_token ?? null;
        if (is_null($request->user_token)) {
            return $this->error(__('apps::frontend.general.user_token_not_found'), [], 422);
        }

        $response = [];
        $delivery_time_types = ['direct', 'schedule'];
        foreach ($delivery_time_types as $key => $value) {
            if ($value == 'schedule') {
                $buildDays = [];
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate = Carbon::today()->addDays(6)->format('Y-m-d');
                $period = CarbonPeriod::create($startDate, $endDate);

                foreach ($period as $index => $date) {
                    $shortDay = Str::lower($date->format('D'));

                    $day = $this->delivery_times->getByDay($shortDay);
                    if ($day) {
                        $times = [];
                        foreach ($day->times as $k => $time) {
                            if (now()->format('Y-m-d H:i') < $date->format('Y-m-d') .' '. Carbon::createFromFormat('H:i A', $time['to'])->format('H:i')) {
                                $times[] = $time;
                            }
                        }

                        if (count($times) > 0) {
                            $customTime = [
                                'date' => $date->format('Y-m-d'),
                                'day_code' => $shortDay,
                                'day_name' => __('company::dashboard.companies.availabilities.days.' . $shortDay),
                                'times' => DeliveryTimeResource::collection($times)
                            ];
                            $buildDays[] = $customTime;
                        } else {
                            $customTime = [
                                'date' => $date->format('Y-m-d'),
                                'day_code' => $shortDay,
                                'day_name' => __('company::dashboard.companies.availabilities.days.' . $shortDay),
                                'times' => DeliveryTimeResource::collection($day->times)
                            ];
                            $buildDays[] = $customTime;
                        }
                    }
                }

                $response[$key]['type'] = $value;
                $response[$key]['title'] = __('deliverytime::webservice.delivery_time_types.schedule');
                $response[$key]['message'] = null;
                $response[$key]['times'] = $buildDays;
                $response[$key]['with_times'] = true;
            } else {
                $response[$key]['type'] = $value;
                $response[$key]['title'] = __('deliverytime::webservice.delivery_time_types.direct');
                $response[$key]['message'] = null;
                $response[$key]['times'] = [];
                $response[$key]['with_times'] = false;
            }
        }

        return $this->response($response);
    }

}
