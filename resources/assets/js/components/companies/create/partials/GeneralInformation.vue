<template>
    <div>
        <!-- Name & area -->
        <div class="row">
            <form-item col="col-6" label="Razón social" :errors="errors.business_name">
                <div class="col">
                    <input type="text" name="business_name" class="form-control" :value="values.business_name" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <form-item col="col-6" label="Nombre" :errors="errors.name">
                <div class="col">
                    <input type="text" name="name" class="form-control" :value="values.name" @input="(e) => update(e.target)">
                </div>
            </form-item>
        </div>
        <!-- Cuit & expiration -->
        <div class="row">
            <form-item col="col-4" label="Rubro" :errors="errors.area">
                <div class="col">
                    <input type="text" name="area" class="form-control" :value="values.area" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <form-item col="col-4" label="CUIT" :errors="errors.cuit">
                <div class="col">
                    <cuil-cuit :value="values.cuit" @input="(value) => update({name: 'cuit', value: value})"/>
                </div>
            </form-item>
            <form-item col="col-4" label="Vencimiento" :errors="errors.expiration">
                <div class="col">
                    <input type="date" name="expiration" class="form-control" :value="values.expiration" @input="(e) => update(e.target)">
                </div>
            </form-item>
        </div>
        <hr>
        <!-- Phone & fax -->
        <div class="row">
            <form-item col="col-6" label="Teléfono" :errors="errors.phone">
                <div class="col">
                    <input type="text" name="phone" class="form-control" :value="values.phone" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <form-item col="col-6" label="Fax" :errors="errors.fax">
                <div class="col">
                    <input type="text" name="fax" class="form-control" :value="values.fax" @input="(e) => update(e.target)">
                </div>
            </form-item>
        </div>
        <!-- Mail & web -->
        <div class="row">
            <form-item col="col-6" label="Mail" :errors="errors.email">
                <div class="col">
                    <input type="email" name="email" class="form-control" :value="values.email" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <form-item col="col-6" label="Página web" :errors="errors.web">
                <div class="col">
                    <input type="text" name="web" class="form-control" :value="values.web" @input="(e) => update(e.target)">
                </div>
            </form-item>
        </div>
        <hr>
        <!-- Residency component -->
        <residency-input :values="residency_values" :errors="residency_errors" @input="v => update(v)"/>
    </div>
</template>

<script>

export default {
    props: {
        values: {
            required: true,
            type: Object
        },
        errors: {
            required: true,
            type: Object
        }
    },
    computed: {
        residency_values: function() {
            return {
                street: this.values.street,
                apartment: this.values.apartment,
                cp: this.values.cp,
                country: this.values.country,
                province: this.values.province,
                city: this.values.city
            }
        },
        residency_errors: function() {
            let direction = [];
            if(this.errors.street && this.errors.apartment) {
                direction = this.errors.street.concat(this.errors.apartment);
            }
            else if(this.errors.street) {
                direction = this.errors.street;
            }
            else if(this.errors.apartment) {
                direction = this.errors.apartment
            }

            return {
                direction: direction,
                cp: this.errors.cp,
                country: this.errors.country,
                province: this.errors.province,
                city: this.errors.city
            }
        },
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'company', properties_path: `values.general_information.${name}`, value: value });
        }
    },
}
</script>

