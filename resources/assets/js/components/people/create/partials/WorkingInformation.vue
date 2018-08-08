<template>
    <div>
        <loading-cover v-if="this.$store.getters.companies.updating || this.$store.getters.activities.updating" message="Cargando..."/>
        <template v-else>
            <!-- Company -->
            <div class="form-row">
                <form-item label="Empresa" :errors="errors.company_id">
                    <div class="col-6">
                        <select2    name="company_id" :value="values.company_id" @input="(value) => update({name: 'company_id', value: value})"
                                    placeholder="Seleccione una empresa" :options="companies"/>
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
            <!-- Activity -->
            <div class="form-row">
                <form-item label="Actividad / Categoría" :errors="errors.activity_id">
                    <div class="col-6">
                        <select2 placeholder="Seleccione una actividad" name="activity_id" :value="values.activity_id" @input="(value) => update({name: 'activity_id', value: value})" :options="activities"/>
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-center">
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
                        <input  type="text" name="art" class="form-control" :value="values.art" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <!-- PBIP -->
            <div class="form-row">
                <form-item col="col-6" label="Vencimiento PBIP" :errors="errors.pbip">
                    <div class="col">
                        <input  type="date" name="pbip" class="form-control" :value="values.pbip" @input="(e) => update(e.target)">
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
            type: Object
        },
        errors: {
            required: true,
            type: Object
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','companies');
        this.$store.dispatch('fetchList','activities');
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.working_information.${name}`, value: value });
        },
    },
    computed: {
        company_id: function() {
            return this.values.company_id;
        },
        companies: function() {
            return this.$store.getters.companies.list.map(company => {
                return {
                    id: company.id,
                    text: company.name,
                }
            });
        },
        activities: function() {
            return this.$store.getters.activities.list.map(activity => {
                return {
                    id: activity.id,
                    text: activity.name,
                }
            });
        }
    }
}
</script>
