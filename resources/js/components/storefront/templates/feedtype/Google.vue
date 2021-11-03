<template>
    <div class="row mb-3">
        <div class="col-md-3 col-12">
            <label class="form-label">Google product category</label>
        </div>
        <div class="col-md-9 col-12">
            <input v-model="formdata.google_product_category" type="text" class="form-control" placeholder="E.g. 2271 or Apparel & Accessories > Clothing > Dresses">
            <div><small>This field will be use as default if your products does not support <a href="https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt" target="_blank">Google product category</a>.</small></div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 col-12">
            <label class="form-label">Product type</label>
        </div>
        <div class="col-md-9 col-12">
            <input v-model="formdata.product_type" type="text" class="form-control" placeholder="E.g. Apparel & Accessories > Clothing > Baby & Toddler Clothing > Baby & Toddler Dresses">
            <div><small>The product type can use the same value for the Google product category. <a href="https://support.google.com/merchants/answer/6324406?hl=en&ref_topic=6324338" target="_blank">Learn more</a></small></div>
        </div>
    </div>
    <div class="row mb-3 position-relative">
        <div class="col-md-3 col-12">
            <label class="form-label">Product data attributes</label>
        </div>
        <div class="col-md-9 col-12">
            <div class="row">
                <div v-for="attr in attributes" :key="attr" class="col-xl-3 col-lg-6 col-12">
                    <label class="mt-0 text-truncate w-100 attribute-title d-flex align-items-center" :title="attr">
                        <input v-model="formdata.attributes" :value="attr" type="checkbox" class="me-2"> {{ attr }}
                    </label>
                </div>
            </div>
            <div class="text-primary"><small>Tick the attribute checkbox if your products support them. <a href="https://support.google.com/merchants/topic/6324338?hl=en&ref_topic=7294998" target="_blank">Learn more</a></small></div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        formdata: {
            google_product_category: undefined,
            product_type: undefined,
            attributes: []
        },
        attributes: [
            'adult', 'multipack', 'is_bundle', 'max_energy_efficiency_class', 'age_group', 'color', 
            'gender', 'material', 'pattern', 'size', 'size_type', 'size_system', 'product_highlight', 
            'ads_redirect', 'custom_label_0–4', 'promotion_id', 'excluded_destination', 'included_destination', 
            'unit_pricing_measure', 'unit_pricing_base_measure', 'subscription_cost', 'loyalty_points', 
            'installment', 'canonical_link'
        ]
    }),
    emits: ['updateContent'],
    watch: {
        'formdata.google_product_category': function(v) {
            this.$emit('updateContent', { google_product_category: v })
        },
        'formdata.product_type': function(v) {
            this.$emit('updateContent', { product_type: v })
        },
        'formdata.attributes': function(v) {
            this.$emit('updateContent', { attributes: v })
        },
    }
}
</script>