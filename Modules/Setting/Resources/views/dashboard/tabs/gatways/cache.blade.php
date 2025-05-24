
<div class="row">
    <div class="col-md-9">

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.supported_countries') }}
            </label>
            <div class="col-md-9">
                <select name="payment_gateway[cache][supported_countries]" class="form-control select2" multiple="" data-placeholder="{{ __('setting::dashboard.settings.form.all_countries') }}">
                    @foreach ($countries as $code => $country)
                        <option value="{{ $code }}"
                                @if (collect(config('setting.payment_gateway.cache.supported_countries',[]))->contains($code))
                                selected=""
                            @endif>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-9">
                @foreach (config('translatable.locales') as $code)

                    {!! field()->text('payment_gateway[cache][title_'.$code.']', __('setting::dashboard.settings.form.payment_gateway.title').'-'.$code ,
                    config('setting.payment_gateway.cache.title_'.$code)) !!}

                @endforeach
                {!! field()->checkBox('payment_gateway[cache][status]', __('setting::dashboard.settings.form.payment_gateway.payment_types.payment_status') , null , [
                (config('setting.payment_gateway.cache.status') == 'on' ? 'checked' : '') => ''
                ]) !!}
            </div>
        </div>
    </div>
</div>
