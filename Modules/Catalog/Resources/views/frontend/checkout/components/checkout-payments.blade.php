<div class="order-payment">
    @foreach($paymentMethods as $k => $payment)

            @if($payment->code == 'online' && count(config('setting.payment_gateway.my_fatoorah')))

                @foreach(config('setting.payment_gateway') as $k => $gateway)

                    @if($gateway['status'] == 'on')
                        <div class="checkboxes radios mb-20">
                            <input type="radio"
                                   value="{{$k}}"
                                   id="gateway-{{$k}}"
                                   name="payment" {{ (old('payment') ?? ($loop->index == 0 ? $k : null) ) == $k  ? 'checked' : '' }}
                            >
                            <label for="gateway-{{$k}}">{{ isset($gateway['title_'.locale()]) ? $gateway['title_'.locale()] : $payment->title}} </label>
                        </div>
                    @endif
                @endforeach
            
            @endif

    @endforeach
</div>