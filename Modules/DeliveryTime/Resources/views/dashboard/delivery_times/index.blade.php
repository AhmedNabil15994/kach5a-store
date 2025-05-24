@extends('apps::dashboard.layouts.app')
@section('title', __('user::dashboard.users.update.title'))
@section('css')

    <style>
        .is_full_day {
            margin-left: 15px;
            margin-right: 15px;
        }

        .collapse-custom-time {
            display: none;
        }

        .times-row {
            margin-bottom: 5px;
        }
    </style>

@endsection
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('user::dashboard.users.update.title')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>


            <div class="tab-pane fade in" id="availabilities">
                <div class="col-md-12">
                    <form id="updateForm" class="form-horizontal form-row-seperated" method="post"
                          action="{{ route('dashboard.delivery_times.update') }}">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('deliverytime::dashboard.day')}}</th>
                            <th>{{__('deliverytime::dashboard.time')}}</th>
                        </tr>
                        </thead>
                            @csrf
                            @method('PUT')
                        <tbody>
                        @foreach(getDays() as $k => $day)
                            @php
                                $delivery_time = count($delivery_times) ? $delivery_times->where('day', $k)->first() : null;
                            @endphp
                            <tr>
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox"
                                               class="group-checkable"
                                               value="1"
                                               {{$delivery_time ? ($delivery_time->status ? 'checked' : '') : ''}}
                                               name="availability[{{$k}}][status]">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    {{ $day }}
                                </td>
                                <td>
                                    <div class="row times-row" id="rowId-{{$k}}-0">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control timepicker 24_format"
                                                       name="availability[{{$k}}][time_from]"
                                                       data-name="availability[{{$k}}][time_from]" value="{{$delivery_time ? $delivery_time->time_from : ''}}">
                                                <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-clock-o"></i>
                                        </button>
                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control timepicker 24_format"
                                                       name="availability[{{$k}}][time_to]"
                                                       data-name="availability[{{$k}}][time_to]" value="{{$delivery_time ? $delivery_time->time_to : ''}}">
                                                <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-clock-o"></i>
                                        </button>
                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                        </div>
                                    </div>

                                </td>
                            </tr>

                        @endforeach
                        </tbody>


                    </table>
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{ __('apps::dashboard.general.edit_btn') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
