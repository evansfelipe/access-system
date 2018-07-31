<template>
    <div>
        <loading-cover v-if="this.$store.state.companies.updating || this.$store.state.activities.updating" message="Cargando..."/>
        <!-- Company -->
        <div class="form-row">
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
                <div class="col-1 text-center">
                    <strong>o</strong>
                </div>
                <div class="col-5">
                    <button type="button" class="btn btn-block btn-outline-unique">
                        Cree una nueva empresa
                    </button>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input">
                        <label class="form-check-label">Monotributista</label>
                    </div>
                </div>
            </form-item>
        </div>
        <!-- Activity -->
        <div class="form-row">
            <form-item label="Actividad / Categoría" :errors="errors.activity_id">
                <div class="col-6">
                    <select name="activity_id" class="form-control" :value="values.activity_id" @input="updateValues" ref="activity_id">
                        <option value="" hidden>Seleccione actividad</option>
                        <option v-for="(activity, key) in activities" :key="key"
                                :value="activity.id">
                                {{ activity.name }}
                        </option>
                    </select>                    
                </div>
                <div class="col-1 text-center">
                    <strong>o</strong>
                </div>
                <div class="col-5">
                    <button type="button" class="btn btn-block btn-outline-unique">
                        Cree una nueva actividad
                    </button>                    
                </div>
            </form-item>
        </div>
        <!-- ART -->
        <div class="form-row">
            <form-item col="col-6" label="ART" :errors="errors.art">
                <div class="col">
                    <input  type="text" name="art" class="form-control" :value="values.art" @input="updateValues" ref="art">
                </div>
            </form-item>
        </div>
        <!-- PBIP -->
        <div class="form-row">
            <form-item col="col-6" label="Vencimiento PBIP" :errors="errors.pbip">
                <div class="col">
                    <input  type="date" name="pbip" class="form-control" :value="values.pbip" @input="updateValues" ref="pbip">
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
    beforeMount() {
        this.$store.dispatch('fetch','companies');
        this.$store.dispatch('fetch','activities');
    },
    methods: {
        updateValues: function() {
            let data = {};
            let keys = Object.keys(this.values);
            keys.forEach(key => {
                data[key] = this.$refs[key].value;
            })
            this.$parent.$emit('working-information-values', data);
        }
    },
    computed: {
        company_id: function() {
            return this.values.company_id;
        },
        companies: function() {
            return this.$store.state.companies.list;
        },
        activities: function() {
            return this.$store.state.activities.list;
        }
    }
}
</script>
