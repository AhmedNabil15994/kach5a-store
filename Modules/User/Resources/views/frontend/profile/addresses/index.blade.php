@extends('apps::frontend.layouts.master-without-dir')
@section('title', __('user::frontend.addresses.index.title'))
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        input[name="mobile"]{
            direction: ltr;
        }
        .help-block {
            color: red;
            font-size: 10px;
            font-weight: 800;
        }
        .iti{
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    @include('user::frontend.profile.components.header' , ['title' => __('user::frontend.addresses.index.title')])
    <div class="previous-address">

        <h2 class="cart-title">{{ __('user::frontend.addresses.index.title')}}</h2>
        @include('apps::frontend.layouts._alerts')

        <div id="address_container">
            @include('user::frontend.profile.addresses.components.addresses')
        </div>

        <div class="cart-footer pt-40 mb-20 mt-20 text-left">
            <button class="btn btn-them main-custom-btn" onclick="openAddressModal()">
                {{ __('user::frontend.addresses.create.title') }}
            </button>
        </div>
    </div>

    @include('user::frontend.profile.addresses.components.address-model',['route' => route('frontend.profile.address.store')])

    @include('user::frontend.profile.components.footer')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
        <script>
            const itiOptions = {
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                preferredCountries: ["kw","ae","bh","sa","om","eg"],
            };

            let itis = [];
            $.each($('.mobile'),function (index,item){
                let keyIndex = $(item).parents('.modal').data('id');
                keyIndex = keyIndex ? parseInt(keyIndex) : 0;
                if(!isNaN(keyIndex)){
                    itis[keyIndex] = intlTelInput(item,itiOptions)
                }
            });

            function setCode(elem,route) {
                let keyIndex = $(".modal.show").data('id');
                keyIndex = keyIndex ? parseInt(keyIndex) : 0;
                if(!isNaN(keyIndex)){
                    let baseNumber = itis[keyIndex].getNumber().replace(' ','').replace('+','');
                    let newNumber  = $('.modal.show .mobile').val().replace(/\s/g,'');

                    $(".modal.show .calling_code").val(baseNumber.replace(newNumber,''));
                    submitForm(elem,route)
                }
            }
        </script>
    @endpush
@endsection
