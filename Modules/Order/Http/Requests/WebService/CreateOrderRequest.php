<?php

namespace Modules\Order\Http\Requests\WebService;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Modules\DeliveryTime\Entities\DeliveryTimes;
use Modules\DeliveryTime\Entities\DeliveryTimesAvailability;

class CreateOrderRequest extends FormRequest
{
    public function rules()
    {
        if ($this->address_type == 'guest_address') {
            $rules = [
                'address.username' => 'nullable|string',
                'address.email' => 'nullable|email',
                'address.state_id' => 'required|numeric',
                'address.mobile' => 'nullable|string',
//                'address.mobile' => 'required|string|min:8|max:8',
                'address.block' => 'nullable|string',
                'address.street' => 'nullable|string',
                'address.building' => 'nullable|string',
                'address.address' => 'nullable|string',
            ];
        } elseif ($this->address_type == 'selected_address') {
            $rules = [
                'address.selected_address_id' => 'nullable',
            ];
        } else {
            $rules = [
                'address_type' => 'required|in:guest_address,selected_address',
            ];
        }

//        $rules['user_id'] = 'nullable|exists:users,id';
        $rules['payment'] = 'required';
        $rules['shipping_company.availabilities.day_code'] = 'nullable';
        $rules['shipping_company.availabilities.day'] = 'nullable';

        $rules['shipping.type'] = 'nullable|in:direct,schedule';
        $rules['shipping.date'] = 'required_if:shipping.type,schedule|date_format:Y-m-d|after_or_equal:today';
        $rules['shipping.time_from'] = 'required_if:shipping.type,schedule';
        $rules['shipping.time_to'] = 'required_if:shipping.type,schedule';

        return $rules;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $messages = [
            'address_type.required' => __('order::api.address.validations.address_type.required'),
            'address_type.in' => __('order::api.address.validations.address_type.in'),
            'selected_address_id.required' => __('order::api.address.validations.selected_address_id.required'),

            'address.username.string' => __('order::api.address.validations.username.string'),
            'address.email.email' => __('order::api.address.validations.email.email'),
            'address.state_id.required' => __('order::api.address.validations.state_id.required'),
            'address.state_id.numeric' => __('order::api.address.validations.state_id.numeric'),
            'address.mobile.required' => __('order::api.address.validations.mobile.required'),
            'address.mobile.numeric' => __('order::api.address.validations.mobile.numeric'),
            'address.mobile.digits_between' => __('order::api.address.validations.mobile.digits_between'),
            'address.mobile.min' => __('order::api.address.validations.mobile.min'),
            'address.mobile.max' => __('order::api.address.validations.mobile.max'),
            'address.address.required' => __('order::api.address.validations.address.required'),
            'address.address.string' => __('order::api.address.validations.address.string'),
            'address.address.min' => __('order::api.address.validations.address.min'),
            'address.block.required' => __('order::api.address.validations.block.required'),
            'address.block.string' => __('order::api.address.validations.block.string'),
            'address.street.required' => __('order::api.address.validations.street.required'),
            'address.street.string' => __('order::api.address.validations.street.string'),
            'address.building.required' => __('order::api.address.validations.building.required'),
            'address.building.string' => __('order::api.address.validations.building.string'),

            'user_id.exists' => __('order::api.orders.validations.user_id.exists'),
            'payment.required' => __('order::api.payment.validations.required'),
            'payment.in' => __('order::api.payment.validations.in') . ' cash,online',

            'shipping_company.availabilities.day_code.required' => __('order::api.shipping_company.validations.day_code.required'),
            'shipping_company.availabilities.day.required' => __('order::api.shipping_company.validations.day.required'),
        ];

        return $messages;
    }

    public function withValidator($validator)
    {
        if (auth('api')->check())
            $userToken = auth('api')->user()->id;
        else
            $userToken = $this->user_id ?? null;

        $validator->after(function ($validator) use ($userToken) {

            if (auth('api')->guest() && is_null($this->user_id)) {
                return $validator->errors()->add(
                    'user_id', __('order::api.orders.validations.user_id.required')
                );
            }

            if (count(getCartContent($userToken)) <= 0) {
                return $validator->errors()->add(
                    'cart', __('order::api.orders.validations.cart.required')
                );
            }

            $companyDeliveryFees = getCartConditionByName($userToken, 'company_delivery_fees');

            if (is_null($companyDeliveryFees)) {
                return $validator->errors()->add(
                    'company_delivery_fees', __('order::api.orders.validations.company_delivery_fees.required')
                );
            }

            if (auth('api')->check() && $companyDeliveryFees != null && empty($companyDeliveryFees->getAttributes()['address_id'])) {
                return $validator->errors()->add(
                    'address_id', __('order::api.orders.validations.address_id.required')
                );
            }

            if (!in_array($this->payment, config('setting.other.supported_payments',[])) && !count(config("setting.payment_gateway.{$this->payment}",[]))) {
                return $validator->errors()->add(
                    'payment', __('order::frontend.orders.index.alerts.payment_not_supported_now')
                );
            }

            $shippingType = $this->shipping['type'] ?? null;
            if ($shippingType == 'schedule') {
                $date = Carbon::parse($this->shipping['date']);
                $day = Str::lower($date->format('D'));
                $deliveryTime = $day = DeliveryTimes::query()->where('day', $day)->first();
                if (!$deliveryTime) {
                    return $validator->errors()->add(
                        'day_not_available',
                        __('order::api.orders.validations.shipping_time.day_not_available')
                    );
                }

                $deliveryTimesAvailability = DeliveryTimesAvailability::query()->where('delivery_time_id', $deliveryTime->id)->where('from', $this->shipping['time_from'])->where('to', $this->shipping['time_to'])->first();
                if (!$deliveryTimesAvailability) {
                    return $validator->errors()->add(
                        'time_not_match',
                        __('order::api.orders.validations.shipping_time.time_not_match')
                    );
                }
            }

        });
        return true;
    }
}
