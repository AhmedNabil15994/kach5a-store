<!DOCTYPE html>
<html dir="{{ locale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>{{ __('order::frontend.orders.invoice.details_title') }}</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    @if (locale() == 'ar')
        <link href="https://cdn.rtlcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

    <style>
        /****************************
    #Invoice Page
    *****************************/
        .invoice-page{
            margin: 80px 0;
            border: 1px solid #dedede;
        }
        .invoice-head {
            background: #000000;
            color: #fff;
            text-align: center;
            font-size: 20px;
            padding: 10px 0;
            margin: 0;
            /*margin-bottom: 30px;*/
        }
        .invoice-page img{
            max-height: 80px;
        }
        .invoice-head-rec {
            padding: 30px;
        }
        .invoice-body {
            padding: 30px;
        }
        table.meta, table.balance {
            width: 100%;
        }
        .invoice-body h1{
            font-size: 20px;
        }
        th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: right}
        th, td { border-radius: 0.25em; border-style: solid; }
        th {
            background: #f9f9f9;
            border-color: #ddd;
        }
        td { border-color: #DDD; }
        table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
        table.meta th { width: 40%; }
        table.meta td { width: 60%; }
        table.inventory {
            clear: both;
            width: 100%;
            margin: 20px 0;
        }
        table.balance {
            width: 50%;
        }
        table.inventory th:first-child {
            width:50px;
        }
        table.inventory th:nth-child(2) {
            width:300px;
        }
        table.inventory th { font-weight: bold; text-align: center; }

        table.inventory td:nth-child(1) { width: 26%; }
        table.inventory td:nth-child(2) { width: 38%; }
        table.inventory td:nth-child(3) { text-align: right; width: 12%; }
        table.inventory td:nth-child(4) { text-align: right; width: 12%; }
        table.inventory td:nth-child(5) { text-align: right; width: 12%; }
        table.balance th, table.balance td { width: 50%; }
        table.balance td { text-align: right; }
        @media print {
            * { -webkit-print-color-adjust: exact; }
            html { background: none; padding: 0; }
            body { box-shadow: none; margin: 0; }
            span:empty { display: none; }
            .add, .cut { display: none; }
        }
        .invoice-footer {
            padding: 30px;
        }
        .invoice-footer .btn {
            font-size: 14px;
            padding: 10px 20px;
        }
        .invoice-style2 {
            margin: 50px auto;
            width: 100%;
        }
        .invoice-style2 .invoice-body h1 {
            font-size: 15px;
            color: #000000;
            font-weight: 600;
        }
        .invoice-style2 thead tr{
            border: none;
            background: #000000;
        }
        .invoice-style2 table.inventory th {
            font-weight: bold;
            text-align: right;
            border: none;
            border-radius: 0;
            color: #fff;
            background: transparent;
            padding: 12px 10px;
        }
        .invoice-style2 td {
            border: none;
            border-radius: 0;
            padding: 12px 10px;
        }
        .invoice-style2 th {
            background: transparent;
            border-color: transparent;
        }
        .invoice-style2 .even{
            background: #f5f5f5;
        }
        .invoice-style2 .balance .price {
            color: #000000;
            font-size: 15px;
        }
        @media (max-width: 991px){
            .invoice-head-rec, .invoice-body {
                padding: 10px;
            }
            .invoice-footer {
                padding: 30px 10px;
            }
            .invoice-style2 {
                margin: 30px auto;
                width: 100%;
            }
            table.balance {
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .text-xs-center {
                text-align: center;
            }
        }

    </style>

</head>
<body>
<div class="container">
    <div class="invoice-page invoice-style2">
        <div class="invoice-conent text-xs-center">
            <h1 class="invoice-head"> {{ __('order::frontend.orders.invoice.title') }}</h1>
            <div class="invoice-head-rec">
                <div class="row">
                    <div class="col-md-8 col-4">
                        <img
{{--                            src="{{ config('setting.logo') ? url(config('setting.logo')) : url('frontend/images/logo.png') }}"--}}
                            src="https://sanfoorkw.com/uploads/settings/2024/2/15/original-170798491884611.png"
                            class="img-fluid">
                    </div>
                    <div class="col-md-4 col-8">
                        <address class="norm">
                            <p class="d-flex"><b class="flex-1">
                                    {{ __('order::frontend.orders.invoice.order_id') }}
                                </b>{{ $order->id }}</p>
                            <p class="d-flex"><b
                                    class="flex-1">{{ __('order::frontend.orders.invoice.date') }}</b>{{ $order->created_at }}
                            <p>
                            <p class="d-flex"><b
                                    class="flex-1">{{ __('order::frontend.orders.invoice.method') }}</b>
                                @if($order->transactions->method == 'cash')
                                    {{ __('order::frontend.orders.invoice.cash') }}
                                @else
                                    {{ __('order::frontend.orders.invoice.online') }}
                                @endif
                            </p>
                        </address>
                    </div>
                </div>
            </div>

            <div class="invoice-body">
                <div class="row">
                    <div class="col-md-4">
                        <h1>{{ __('order::frontend.orders.invoice.client_address.receiver') }}</h1>


                        @php $address = $order->orderAddress ?? $order->unknownOrderAddress; @endphp
                        @if ($address)
                            <address class="norm">
                                <p class="d-flex">
                                    <b class="flex-1"><span class="bold uppercase">
                                        {{ optional(optional(optional($address)->state)->city)->title }}
                                        /
                                        {{ optional(optional($address)->state)->title }}
                                    </span>
                                </p>
                                <p class="d-flex">
                                    <b class="flex-1">

                                        @if(optional($address)->address_type != 'local')
                                            <span class="bold uppercase">
                                            @if(optional($address)->json_data['country_id'])
                                                    {{Modules\Area\Entities\Country::find(optional($address)->json_data['country_id'])->title}}
                                                    /
                                                @endif
                                                @if(optional($address)->json_data['city'])
                                                    {{optional($address)->json_data['city']}}
                                                @endif
                                        </span>
                                @endif
                                <p>
                                @if (optional($address)->address_type)
                                    <p class="d-flex">
                                        <b class="flex-1">{{ __('order::dashboard.orders.show.address.shipping_type') }}
                                            :</b>
                                        {{ optional($address)->address_type }}
                                    </p>
                                @endif
                                <p class="d-flex">
                                    <b class="flex-1">{{ __('order::dashboard.orders.show.address.details') }}
                                        :</b>
                                    {{ optional($address)->address ?? '---' }}
                                </p>
                                @php $addresstAttrs = optional(optional(optional($address))->attributes())->get(['name','value','type']); @endphp

                                @foreach($addresstAttrs as $attr)
                                    @if($attr->type == 'file')
                                        <p class="d-flex">
                                            <b class="flex-1">
                                                {{$attr->name}} :</b>
                                            <a href="{{asset($attr->value)}}"><i class="fa fa-file"></i></a>
                                        </p>
                                    @elseif($attr->type == 'countryAndStates' && optional($address)->address_type == 'local')
                                        <p class="d-flex">
                                            <b class="flex-1">
                                                {{$attr->name}} :</b>

                                            @inject('states','Modules\Area\Entities\State')
                                            @php $state = optional(optional($states)->find($attr->value)) @endphp

                                            @if(optional($address)->address_type == 'local')
                                                {{ optional(optional(optional($state)->city)->country)->title }}
                                                /
                                                {{ optional(optional($state)->city)->title }}
                                                /
                                                {{ optional($state)->title }}
                                            @endif
                                        </p>
                                    @else
                                        <p class="d-flex">
                                            <b class="flex-1">
                                                {{$attr->name}} :</b>
                                            {{$attr->value}}
                                        </p>
                                    @endif
                                @endforeach
                            </address>
                        @endif
                    </div>
                    <div class="col-md-4"></div>
                </div>

                <table class="inventory">
                    <thead>
                    <tr>
                        <th><span> #</span></th>
                        <th><span>{{ __('order::frontend.orders.invoice.product_title') }}</span></th>
                        <th><span>{{ __('order::frontend.orders.invoice.product_qty') }}</span></th>
                        <th><span>{{ __('order::frontend.orders.invoice.product_price') }}</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($order->orderProducts) > 0)
                        @foreach ($order->orderProducts as $key => $orderProduct)
                            <tr class="{{ ++$key % 2 == 0 ? 'even' : '' }}">
                                <td><span>{{ $key }}</span></td>
                                @if (isset($orderProduct->product_variant_id) || $orderProduct->product_variant_title)
                                    <td>
                                            <span>
                                                {!!
                                                    $orderProduct->product_variant_id ?
                                                        generateVariantProductData($orderProduct->variant->product,
                                                        $orderProduct->product_variant_id,
                                                        $orderProduct->variant->productValues->pluck('option_value_id')->toArray())['name']
                                                    :
                                                        $orderProduct->product_variant_title
                                                !!}
                                            </span>
                                    </td>
                                @else
                                    <td><span>{{ $orderProduct->product_id ? $orderProduct->product->title : $orderProduct->product_title }}</span></td>
                                @endif
                                <td><span>{{ $orderProduct->qty }}</span></td>
                                <td><span>{{ $orderProduct->price }}</span>
                                    <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

                <table class="balance">
                    <tr>
                        <th><span>{{ __('order::frontend.orders.invoice.subtotal') }}</span></th>
                        <td>
                            <span>{{ $order->subtotal }}</span>
                            <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th><span>{{ __('order::frontend.orders.invoice.shipping') }}</span></th>
                        <td>
                            <span>{{ $order->shipping }}</span>
                            <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                        </td>
                    </tr>
                    <tr class="price">
                        <th><span>{{ __('order::frontend.orders.invoice.total') }}</span></th>
                        <td>
                            <span>{{ $order->total }}</span>
                            <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
{{--        <div class="invoice-footer">--}}
{{--            <button class="btn btn-them print-invoice main-custom-btn"><i class="ti-printer"></i> {{ __('order::frontend.orders.invoice.btn.print') }}</button>--}}
{{--        </div>--}}
    </div>
</div>
<script>
    $('.print-invoice').click(function () {
        window.print();
    });
</script>
</body>
</html>
