<template>
    <div>
        <loading-cover v-if="this.$store.getters.companies.updating || this.$store.getters.vehicle_types.updating"/>
        <template v-else>
            <div class="form-row">
                <!-- Type -->
                <form-item col="col-4" label="Tipo" :errors="errors.type">
                    <div class="col">
                        <select2 placeholder="Seleccione un tipo" :value="type_id" :options="types"
                                    @input="(value) => update({name: 'type_id', value: value})"/>
                    </div>
                </form-item>
                <!-- Plate -->
                <form-item col="col-4" label="Patente" :errors="errors.plate">
                    <div class="col">
                        <input type="text" name="plate" class="form-control" :value="values.plate" @input="(e) => update(e.target)">
                    </div>
                </form-item>
                <!-- Year -->
                <form-item col="col-4" label="Año" :errors="errors.year">
                    <div class="col">
                        <input type="number" min="1900" :max="(new Date()).getFullYear()" name="year" class="form-control" :value="values.year" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <div class="form-row">
                <!-- Brand -->
                <form-item col="col-4" label="Marca" :errors="errors.brand">
                    <div class="col">
                        <input type="text" name="brand" class="form-control" :value="values.brand" @input="(e) => update(e.target)">
                    </div>
                </form-item>
                <!-- Model -->
                <form-item col="col-4" label="Modelo" :errors="errors.model">
                    <div class="col">
                        <input type="text" name="model" class="form-control" :value="values.model" @input="(e) => update(e.target)">
                    </div>
                </form-item>
                <!-- Colour -->
                <form-item col="col-4" label="Color" :errors="errors.colour">
                    <div class="col">
                        <input type="text" name="colour" class="form-control" :value="values.colour" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <div class="form-row">
                <!-- Company -->
                <form-item col="col-6" label="Empresa" :errors="errors.company_id">
                    <div class="col">
                        <select2    name="company_id" :value="values.company_id" @input="(value) => update({name: 'company_id', value: value})"
                                    placeholder="Seleccione una empresa" :options="companies"/>
                    </div>
                </form-item>
                <!-- Owner -->
                <form-item col="col-6" label="Titular del vehículo" :errors="errors.owner">
                    <div class="col">
                        <input type="text" name="owner" class="form-control" :value="values.owner" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <div class="form-row">
                <div class="col-12"><hr-label>Vencimientos</hr-label></div>
                <!-- Insurance -->
                <form-item col="col-6" label="Seguro" :errors="errors.insurance">
                    <div class="col">
                        <date-picker :value="values.insurance" @input="value => update({name: 'insurance', value: value})"/>
                    </div>
                </form-item>
                <!-- VTV -->
                <form-item col="col-6" label="VTV" :errors="errors.vtv">
                    <div class="col">
                        <date-picker :value="values.vtv" @input="value => update({name: 'vtv', value: value})"/>
                    </div>
                </form-item>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        values: {
            required: true,
            type: Object,
        },
        errors: {
            required: true,
            type: Array,
        }
    },
    computed: {
        companies: function() {
            return this.$store.getters.companies.asOptions();
        },
        types: function() {
            return this.$store.getters.vehicle_types.asOptions('type');
        },
        company_id: function() {
            return this.values.company_id;
        },

        type_id: function() {
            return this.values.type_id;
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','vehicle_types');
        this.$store.dispatch('fetchList','companies');
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'vehicle', properties_path: `values.general_information.${name}`, value: value });
        }
    },
}
</script>
