<div class="row">
    <div class="mt-radio-inline text-center">
        <label class="mt-radio mt-radio-outline">
            {{ __('catalog::dashboard.products.form.product_type.single') }}
            <input type="radio" class="product_type" name="shipment[product_type]" value="single"
                {{ isset($product) && $product->variants()->count() ? '' : 'checked'   }}>
            <span></span>
        </label>
        <label class="mt-radio mt-radio-outline">
            {{ __('catalog::dashboard.products.form.product_type.multiple') }}
            <input type="radio" class="product_type" name="shipment[product_type]" value="multiple"
                {{ isset($product) && $product->variants()->count() ? 'checked' : ''   }}>
            <span></span>
        </label>
    </div>
</div>
