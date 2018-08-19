<style lang="scss" scoped>
    div.container.job {
        position: relative;
        border-radius: 3px;
        border: 1px solid rgb(222,222,222);
        padding: 1em;
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.companies.updating || this.$store.getters.activities.updating" message="Cargando..."/>
        <template v-else>
            <!-- Risk , PBIP & ART -->
            <div class="form-row">
                <form-item label="Nivel de riesgo" :errors="errors.risk">
                    <div class="col">
                        <select2    name="risk" :value="values.risk" @input="(value) => update({name: 'risk', value: value})"
                                    placeholder="Seleccione un nivel de riesgo" :options="risk_levels"/>
                    </div>
                </form-item>
                <form-item col="col-6" label="Vencimiento PBIP" :errors="errors.pbip">
                    <div class="col">
                        <input  type="date" name="pbip" class="form-control" :value="values.pbip" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <div class="row">
                <form-item col="col-4" label="ART" :errors="errors.art">
                    <div class="col">
                        <input  type="text" name="art" class="form-control" :value="values.art" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <!-- Jobs -->
            <div class="row">
                <div class="col">
                    <h6>Trabajos:</h6>
                </div>
            </div>
            <div class="container job mb-2" v-for="(job, index) in values.jobs" :key="job.key">
                <div class="row">
                    <div class="col" style="text-align: right">
                        <i class="btn-remove far fa-trash-alt" v-if="values.jobs.length > 1" @click="deleteJob(job)"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <!-- Company, Activity & Subactivities -->
                        <job-data   :job="{ company_id: job.company_id, activity_id: job.activity_id, subactivities: job.subactivities }" 
                                    :companies="companies" :activities="activities" :errors="jobs_errors[index] || []"
                                    @change="(data) => editJob(job, data)"
                        />
                        <!-- Card number, Card from & Card until -->
                        <h6>Tarjetas:</h6>
                        <job-cards  :cards="job.cards" @change="(data) => editJobCards(job, data)"
                                    @add="addCardToJob(job)" @edit="({card, data}) => editCardFromJob(job, card, data)" @remove="card => removeCardFromJob(job, card)"
                                    :errors="jobs_errors[index] ? jobs_errors[index].cards : []"
                        />
                    </div>
                </div>
            </div>
            <!-- Add job button -->
            <div class="container mt-3">
                <div class="row">
                    <div class="col text-right">
                        <button class="btn btn-link" title="Añadir nueva empresa y actividad" @click="addJob">
                            <i class="fas fa-plus"></i> Agregar trabajo
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    components: {
        'job-data':  require('./partials/JobData'),
        'job-cards': require('./partials/Cards'),
    },
    props: {
        values: {
            required: true,
            type: Object
        },
        errors: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            risk_levels: [
                { id: 1, text: 'Nivel 1' },
                { id: 2, text: 'Nivel 2' },
                { id: 3, text: 'Nivel 3' },
            ],
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','companies');
        this.$store.dispatch('fetchList','activities');
    },
    methods: {
        addJob: function() {
            this.$store.commit('addJob');
        },
        editJob: function(job, data) {
            this.$store.commit('updateJob', {job, data});
        },
        deleteJob: function(job) {
            this.$store.commit('deleteJob', job);
        },
        addCardToJob: function(job) {
            this.$store.commit('addCardToJob', job);
        },
        editCardFromJob: function(job, card, data) {
            this.$store.commit('editCardFromJob', {job, card, data});
        },
        removeCardFromJob: function(job, card) {
            this.$store.commit('removeCardFromJob', {job, card});
        },
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.working_information.${name}`, value: value });
        },
    },
    computed: {
        companies: function() {
            return this.$store.getters.companies.list.map(c => { return { id: c.id, text: c.name }});
        },
        activities: function() {
            return this.$store.getters.activities.list.map(a => { return { id: a.id, text: a.name }});
        },
        jobs_errors: function() {
            return this.errors['jobs'] ? this.errors['jobs'] : [];
        }
    }
}
</script>
