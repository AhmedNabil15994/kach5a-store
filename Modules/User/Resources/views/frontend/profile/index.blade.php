@extends('apps::frontend.layouts.master-without-dir')
@section('title', __('user::frontend.profile.index.title'))
@push('plugins_styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        input[name="mobile"]{
            direction: ltr;
        }
        .iti{
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    @include('user::frontend.profile.components.header' , ['title' => __('user::frontend.profile.index.title')])
    <form method="post" action="{{ url(route('frontend.profile.update')) }}" class="contact-form" onsubmit="setCode()">
        @csrf
        <input type="hidden" name="type" value="profile">

        <div class="previous-address">
            @include('apps::frontend.layouts._alerts')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" value="{{ auth()->user()->name }}" name="name"
                               class="form-control" placeholder="{{ __('user::frontend.profile.index.form.name') }}"/>

                        @error('name')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control"
                               placeholder="{{ __('user::frontend.profile.index.form.email') }}"/>
                        @error('email')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control inputTel" name="mobile"
                               value="{{ auth()->user()->mobile ?  ('+'.auth()->user()->calling_code.auth()->user()->mobile) : ''}}"
                               placeholder="{{ __('user::frontend.profile.index.form.mobile') }}" pattern='[0-9]+'/>
                        <input type="hidden" name="calling_code">
                        @error('mobile')
                            <p class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                {!! field('frontend_no_label')->select('currency_id',
                  __('user::frontend.addresses.form.select_currency'),$supported_currencies ,
                    isset(session()->get('currency_data')['selected_currency']) ? optional(session()->get('currency_data')['selected_currency'])->id :
                   defaultCurrency('id'),[
                      'class' => 'select-detail select2 form-control',
                ]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="password" class="form-control"
                               placeholder="{{ __('user::frontend.profile.index.form.password') }}"/>
                        @error('password')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="{{ __('user::frontend.profile.index.form.password_confirmation') }}"/>

                        @error('password_confirmation')
                        <p class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-20 mt-20 text-left">
            <button class="btn btn-them main-custom-btn"
                    type="submit">{{ __('user::frontend.profile.index.form.btn.update') }}</button>
            <a href="{{route('frontend.logout')}}" class="btn btn-them main-custom-btn"
               type="submit">{{ __('user::frontend.profile.index.form.btn.logout') }}</a>
        </div>
    </form>
    @include('user::frontend.profile.components.footer')
    @php
        $keys = \Modules\Area\Entities\Country::whereIn('id',config('setting.supported_countries'))->pluck('iso3')->toArray();
        $default = \Modules\Area\Entities\Country::find(Setting::get('default_country'))->iso3 ?? '';
    @endphp
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
        <script>
            const itiOptions = {
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                initialCountry: "{{$default}}",
                onlyCountries: @json($keys),
            };

            const input1 = document.querySelector(".inputTel");
            const iti1 = intlTelInput(input1,itiOptions)

            function setCode() {
                let baseNumber = iti1.getNumber().replace(' ','').replace('+','');
                let newNumber  = $('.inputTel').val().replace(/\s/g,'');
                $('.inputTel').val(newNumber)
                $("input[name='calling_code']").val(baseNumber.replace(newNumber,''));
            }
        </script>
    @endpush
@endsection
