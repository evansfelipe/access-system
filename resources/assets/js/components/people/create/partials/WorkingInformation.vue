<style lang="scss" scoped>
    div.job-row {
        i.delete-job {
            position: absolute;
            right: .5em;
            color: #CC0000;
            display: none;
        }
        &:hover i.delete-job {
            display: inline;
        }
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.companies.updating || this.$store.getters.activities.updating" message="Cargando..."/>
        <template v-else>
            <!-- Risk & PBIP-->
            <div class="form-row">
                <form-item label="Riesgo" :errors="errors.risk">
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
            <!-- Company & Activity -->
            <div v-for="(job, key) in jobs" :key="key" class="form-row job-row">
                <div class="col-12 text-right">
                    <hr>
                    <button v-if="jobs.length > 1" class="btn btn-link p-0" @click="removeJob(job)">
                        <i class="fas fa-minus-circle delete-job"></i>
                    </button>
                </div>
                <form-item col="col-4" label="Empresa" :errors="jobs_errors[key] ? jobs_errors[key].company_id : []">
                    <div class="col">
                        <select2    name="company_id" :value="job.company_id" @input="(value) => editJob({key: key, attribute: 'company_id' ,value: value})"
                                    placeholder="Seleccione una empresa" :options="companies"/>
                    </div>
                </form-item>
                <form-item col="col-4" label="Actividad" :errors="jobs_errors[key] ? jobs_errors[key].activity_id : []">
                    <div class="col">
                        <select2 placeholder="Seleccione una actividad" name="activity_id" :value="job.activity_id" @input="(value) => editJob({key: key, attribute: 'activity_id' ,value: value})" :options="activities"/>
                    </div>
                </form-item>
                <form-item col="col-4" label="Subactividad/es" :errors="jobs_errors[key] ? jobs_errors[key].subactivities : []">
                    <div class="col">
                        <select2 :tags="true" placeholder="Seleccione una actividad" :name="'subactivities'" :multiple="true" :options="test" :value="[1,2]" @input="(value) => editJob({key: key, attribute: 'subactivities' ,value: value})"/>
                    </div>
                </form-item>
            </div>
            <div class="row">
                <div class="col text-right mt-2">
                    <button class="btn btn-link p-0" title="Añadir nueva empresa y actividad" @click="addJob">
                        <i class="fas fa-plus-circle fa-2x"></i>
                    </button>
                </div>
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
    data() {
        return {
            risk_levels: [
                {id: 1, text: 'Nivel 1'},
                {id: 2, text: 'Nivel 2'},
                {id: 3, text: 'Nivel 3'},
            ],
            test: [
                {id: 1, text: 'Nivel 1'},
                {id: 2, text: 'Nivel 2'},
                {id: 3, text: 'Nivel 3'},
            ],
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','companies');
        this.$store.dispatch('fetchList','activities');
    },
    methods: {
        addJob: function() {
            let copy = this.clone(this.jobs);
            copy.push({
                company_id: '',
                activity_id: '',
                subactivities: [],
            });
            this.jobs = copy;
        },
        removeJob: function(job) {
            let pos = this.jobs.indexOf(job);
            let copy = this.clone(this.jobs);
            this.$delete(copy, pos);
            this.jobs = copy;
        },
        editJob: function({key, attribute, value}) {
            let copy = this.clone(this.jobs);
            copy[key][attribute] = value;
            this.jobs = copy;
        },
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
        },
        jobs: {
            get: function() {
                let obj = {
                    company_id: '',
                    activity_id: '',
                    subactivities: []
                };
                return this.values.jobs.length > 0 ? this.values.jobs : [obj];
            },
            set: function(value) {
                this.$store.commit('updateModel', { which: 'person', properties_path: `values.working_information.jobs`, value: value });
            }
        },
        jobs_errors: function() {
            let errors = {};
            let keys = Object.keys(this.errors);
            keys.forEach(error => {
                if(error.startsWith('jobs.')) {
                    let tokens = error.split('.');
                    if(!errors[tokens[1]]) {
                        errors[tokens[1]] = {};
                    }
                    errors[tokens[1]][tokens[2]] = this.errors[error];
                }
            });
            return errors;
        }
    },
}
</script>
