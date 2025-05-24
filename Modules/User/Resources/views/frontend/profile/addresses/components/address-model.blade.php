@php
    $model = !empty($model) ? $model : null;
@endphp

<div class="modal fade " id="addressModel{{optional($model)['id']}}" data-id="{{optional($model)['id']}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">تعديل العنوان</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="mt-20" method="post" action="{{$route}}">
                    @csrf
                    <input type="hidden" name="view" value="{{!empty($view_type) ? $view_type : ''}}">
                    <input type="hidden" name="calling_code" class="calling_code" value="">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! field('frontend_no_label')->text('username',__('user::frontend.addresses.form.username'),optional($model)->username) !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            {!! field('frontend_no_label')->text('mobile',__('user::frontend.addresses.form.mobile'),(isset($model) ? '+'.optional($model)->mobile : '') , ['id' => '' , 'class' => 'form-control mobile',]) !!}
                        </div>

                        @php
                            $addressesAttributesData = [
                                'selected_country' => !empty($model) && $model ?
                                (isset($model->json_data['country_id']) ? $model->json_data['country_id'] : optional(optional(optional($model)->state)->city)->country_id) : null,
                                'selected_state' => !empty($model) && $model ? optional($model)->state_id : null,
                                'container_class' => 'col-md-6 col-12',
                            ];
                        @endphp
                        <x-attributes-inputs type="addresses" inputTheme="address_no_label" :data="$addressesAttributesData"/>

                    </div>
                    <div class="mb-20 mt-20 text-left">
                        <button class="btn btn-them main-custom-btn" type="button"
                                onclick="setCode(this,'{{$model ? 'update':'create'}}')">
                            <span class="btn-title">{{__('apps::frontend.Save changes')}}</span>

                            <span class="spinner-border spinner-border-md btn_spinner" role="status"
                                  aria-hidden="true"
                                  style="display: none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
