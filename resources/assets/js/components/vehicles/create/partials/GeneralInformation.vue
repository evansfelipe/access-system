<template>
    <div>
        <loading-cover v-if="this.$store.getters.companies.updating" message="Cargando..."/>
        <template v-else>
            <div class="form-row">
                <!-- Type -->
                <form-item col="col-3" label="Tipo" :errors="errors.type">
                    <div class="col">
                        <input type="text" name="type" class="form-control" :value="values.type" @input="updateValues" ref="type">
                    </div>
                </form-item>
                <!-- Plate -->
                <form-item col="col-3" label="Patente" :errors="errors.plate">
                    <div class="col">
                        <input type="text" name="plate" class="form-control" :value="values.plate" @input="updateValues" ref="plate">
                    </div>
                </form-item>
                <!-- Owner -->
                <form-item col="col-6" label="Titular" :errors="errors.owner">
                    <div class="col">
                        <input type="text" name="owner" class="form-control" :value="values.owner" @input="updateValues" ref="owner">
                    </div>
                </form-item>
            </div>
            <div class="form-row">
                <!-- Brand -->
                <form-item col="col-3" label="Marca" :errors="errors.brand">
                    <div class="col">
                        <input type="text" name="brand" class="form-control" :value="values.brand" @input="updateValues" ref="brand">
                    </div>
                </form-item>
                <!-- Model -->
                <form-item col="col-3" label="Modelo" :errors="errors.model">
                    <div class="col">
                        <input type="text" name="model" class="form-control" :value="values.model" @input="updateValues" ref="model">
                    </div>
                </form-item>
                <!-- Colour -->
                <form-item col="col-3" label="Color" :errors="errors.colour">
                    <div class="col">
                        <input type="text" name="colour" class="form-control" :value="values.colour" @input="updateValues" ref="colour">
                    </div>
                </form-item>
                <!-- Year -->
                <form-item col="col-3" label="Año" :errors="errors.year">
                    <div class="col">
                        <input type="number" min="1900" :max="(new Date()).getFullYear()" name="year" class="form-control" :value="values.year" @input="updateValues" ref="year">
                    </div>
                </form-item>
            </div>
            <hr>
            <div class="form-row">
                <!-- Company -->
                <form-item label="Empresa" :errors="errors.company_id">
                    <div class="col-6">
                        <select name="company_id" class="form-control" :value="values.company_id" @input="updateValues" ref="company_id"> 
                            <option value="" hidden>Seleccione una empresa</option>
                            <option v-for="(company, key) in companies" :key="key"
                                    :value="company.id">
                                    {{ company.name }}
                            </option>                        
                        </select>
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-center">
                        <strong>o</strong>
                    </div>
                    <div class="col-5">
                        <button type="button" class="btn btn-block btn-outline-unique">
                            Cree una nueva empresa
                        </button>
                    </div>
                </form-item>
            </div>
            <hr>
            <div class="form-row">
                <div class="col-12"><h6>Vencimientos</h6></div>
                <!-- Insurance -->
                <form-item col="col-6" label="Seguro" :errors="errors.insurance">
                    <div class="col">
                        <input type="date" name="insurance" class="form-control" :value="values.insurance" @input="updateValues" ref="insurance">
                    </div>
                </form-item>
                <!-- VTV -->
                <form-item col="col-6" label="VTV" :errors="errors.vtv">
                    <div class="col">
                        <input type="date" name="vtv" class="form-control" :value="values.vtv" @input="updateValues" ref="vtv">
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
            type: Object,
        }
    },
    computed: {
        companies: function() {
            return this.$store.getters.companies.list;
        },
        company_id: function() {
            return this.values.company_id;
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','companies');
    },
    methods: {
        updateValues: function() {
            let data = {};
            let keys = Object.keys(this.values);
            keys.forEach(key => {
                data[key] = this.$refs[key].value;
            })
            this.$store.commit('updateModel', { which: 'vehicle', properties_path: 'values.general_information', value: data});
        }
    },
}
</script>
