<template>
    <div>
        <!-- Name & area -->
        <div class="row">
            <form-item col="col-6" label="Razón social" :errors="errors.name">
                <div class="col">
                    <input type="text" name="name" class="form-control" :value="values.name" @input="updateValues" ref="name">
                </div>
            </form-item>
            <form-item col="col-6" label="Rubro" :errors="errors.area">
                <div class="col">
                    <input type="text" name="area" class="form-control" :value="values.area" @input="updateValues" ref="area">
                </div>
            </form-item>
        </div>
        <!-- Cuit & expiration -->
        <div class="row">
            <form-item col="col-6" label="CUIT" :errors="errors.cuit">
                <div class="col">
                    <input type="text" name="cuit" class="form-control" :value="values.cuit" @input="updateValues" ref="cuit">
                </div>
            </form-item>
            <form-item col="col-6" label="Vencimiento" :errors="errors.expiration">
                <div class="col">
                    <input type="date" name="expiration" class="form-control" :value="values.expiration" @input="updateValues" ref="expiration">
                </div>
            </form-item>
        </div>
        <hr>
        <!-- Phone & fax -->
        <div class="row">
            <form-item col="col-6" label="Teléfono" :errors="errors.phone">
                <div class="col">
                    <input type="text" name="phone" class="form-control" :value="values.phone" @input="updateValues" ref="phone">
                </div>
            </form-item>
            <form-item col="col-6" label="Fax" :errors="errors.fax">
                <div class="col">
                    <input type="text" name="fax" class="form-control" :value="values.fax" @input="updateValues" ref="fax">
                </div>
            </form-item>
        </div>
        <!-- Mail & web -->
        <div class="row">
            <form-item col="col-6" label="Mail" :errors="errors.email">
                <div class="col">
                    <input type="email" name="email" class="form-control" :value="values.email" @input="updateValues" ref="email">
                </div>
            </form-item>
            <form-item col="col-6" label="Página web" :errors="errors.web">
                <div class="col">
                    <input type="text" name="web" class="form-control" :value="values.web" @input="updateValues" ref="web">
                </div>
            </form-item>
        </div>
        <hr>
        <!-- Street, apartment & zip code -->
        <div class="form-row">
            <form-item col="col-8" label="Domicilio" :errors="direction_errors">
                <div class="col-6">
                    <input type="text" name="street" class="form-control" placeholder="Calle y número" :value="values.street" @input="updateValues" ref="street">
                </div>
                <div class="col-6">
                    <input type="text" name="apartment" class="form-control" placeholder="Departamento" :value="values.apartment" @input="updateValues" ref="apartment">
                </div>
            </form-item>
            <form-item col="col-4" label="Código Postal" :errors="errors.cp">
                <div class="col">
                    <input type="text" name="cp" class="form-control" :value="values.cp" @input="updateValues" ref="cp">                                                                    
                </div>
            </form-item>           
        </div>
        <!-- Country, Province & City -->
        <div class="form-row">
            <form-item col="col-4" label="País" :errors="errors.country">
                <div class="col">
                    <select name="country" class="form-control" :value="values.country" @input="updateValues" ref="country">
                        <option value="" hidden>Seleccione país</option>
                        <option value="Argentina">Argentina</option>
                    </select>
                </div>
            </form-item>
            <form-item col="col-4" label="Provincia / Estado" :errors="errors.province">
                <div class="col">
                    <select name="province" class="form-control" :value="values.province" @input="updateValues" ref="province">
                        <option value="" hidden>Seleccione provincia / estado</option>
                        <option value="Buenos Aires">Buenos Aires</option>
                    </select>
                </div>
            </form-item>
            <form-item col="col-4" label="Ciudad" :errors="errors.city">
                <div class="col">
                    <select name="city" class="form-control" :value="values.city" @input="updateValues" ref="city">
                        <option value="" hidden>Seleccione ciudad</option>
                        <option value="Mar del Plata">Mar del Plata</option>
                    </select>
                </div>
            </form-item>          
        </div>
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
        direction_errors: function() {
            if(this.errors.street && this.errors.apartment) {
                return this.errors.street.concat(this.errors.apartment);
            }
            else if(this.errors.street) {
                return this.errors.street;
            }
            else if(this.errors.apartment) {
                return this.errors.apartment
            }
            else {
                return [];
            }
        },
    },
    methods: {
        updateValues: function() {
            let data = {};
            let keys = Object.keys(this.values);
            keys.forEach(key => {
                data[key] = this.$refs[key].value;
            });
            this.$store.commit('updateModel', { which: 'company', properties_path: 'values.general_information', value: data });
        }
    },
}
</script>

