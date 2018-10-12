<style lang="scss" scoped>
    .job-enter-active, .job-leave-active { transition: all .5s }
    .job-enter, .job-leave-to { opacity: 0 }
    .job-enter { transform: translateX(-10px) }
    .job-leave-active { max-height: 100vh }
    .job-leave-to {
        max-height: 0;
        transform: translateX(10px);
    }

    .btn-link {
        padding: 0;
        text-decoration: none;
    }

    .btn-remove {
        position: absolute;
        right: 15px;
        z-index: 10;
        cursor: pointer;
        color: #CC0000;
    }
</style>

<template>
    <div>
        <loading-cover v-if="updating"/>
        <template v-else>
            <transition-group name="job" tag="div">
                <div class="grey-border mb-2" v-for="job in values.jobs" :key="job.key">
                    <div class="form-row">
                        <div class="col" style="text-align: right">
                            <i class="btn-remove far fa-trash-alt" v-if="values.jobs.length > 1" @click="deleteJob(job)"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <!-- Company, Activity & Subactivities -->
                            <job-data   :job="job"  :companies="companies" :activities="activities"
                                        :errors="jobs_errors[job.key] || []" @change="({attribute, value}) => editJob(job, attribute, value)"
                            />
                        </div>
                        <div class="col-12">
                            <hr-label>Tarjeta{{ job.cards.length > 1 ? 's' : '' }}</hr-label>
                        </div>
                        <div class="col-12">
                            <!-- Card number, Card from & Card until -->
                            <job-cards  :cards="job.cards" :errors="jobs_errors[job.key] ? jobs_errors[job.key].cards : []"
                                        @add="addCardToJob(job)" @edit="({card, attribute, value}) => editCardFromJob(job, card, attribute, value)"
                                        @remove="card => removeCardFromJob(job, card)"
                            />
                        </div>
                    </div>
                </div>
            </transition-group>
            <!-- Add job button -->
            <div class="mt-3">
                <div class="row">
                    <div class="col text-right">
                        <button class="btn btn-link" @click="addJob">
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
            jobs_errors: [],
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'companies');
        this.$store.dispatch('fetchList', 'activities');
        // Used on childs. Called here so it is not fetched once for every added child.
        this.$store.dispatch('fetchList', 'groups');
        this.$store.dispatch('fetchList', 'subactivities');
    },
    computed: {
        updating: function() {
            return  this.$store.getters.groups.updating ||
                    this.$store.getters.companies.updating ||
                    this.$store.getters.activities.updating ||
                    this.$store.getters.subactivities.updating;
        },
        companies: function() {
            return this.$store.getters.companies.asOptions();
        },
        activities: function() {
            return this.$store.getters.activities.asOptions();
        }
    },
    methods: {
        addJob: function() {
            this.$store.commit('addJob');
        },
        editJob: function(job, attribute, value) {
            this.$store.commit('updateJob', {job_key: job.key, attribute, value});
        },
        deleteJob: function(job) {
            this.$store.commit('deleteJob', job);
        },
        addCardToJob: function(job) {
            this.$store.commit('addCardToJob', job);
        },
        editCardFromJob: function(job, card, attribute, value) {
            this.$store.commit('editCardFromJob', {job, card, attribute, value});
        },
        removeCardFromJob: function(job, card) {
            this.$store.commit('removeCardFromJob', {job, card});
        }
    },
    watch: {
        errors: {
            handler: function() {
                /**
                 * The jobs error array is indexed using the position of each job within the array of jobs sent to the server.
                 * This means that, for example, the error under index 2 corresponds to the job that is in position 2 of 
                 * the jobs array. For the correct rendering, we need that the array of jobs errors is indexed using the key of
                 * each job, instead of its position in the array of jobs (because this is the key we are using on v-for).
                 * 
                 * We don't need to update the jobs error array when a job is deleted (or created or updated) because, as we had mapped 
                 * the index already, we can use the same array we created. When a job that had been validate is deleted, the validation
                 * errors remains in the jobs errors array, but as the key of that job has been removed, that component of the jobs
                 * erros arrays wont be accessed again. It is more, doing this brings mapping errors, since there would be more errors than
                 * works to map.
                 */
                this.jobs_errors = [];
                let errors = this.errors['jobs'] ? this.errors['jobs'] : [];
                
                errors.forEach((e, index) => {
                    if(this.values.jobs[index]) {
                        let key = this.values.jobs[index].key;
                        this.jobs_errors[key] = e;
                        /**
                         * The same happens with the cards errors of each job's error. We need to map that index to 
                         * the correspondent card's key.
                         */
                        let cards_errors = [];
                        if(e.cards) {
                            e.cards.forEach((e2, i2) => {
                                if(this.values.jobs[index].cards[i2]) {
                                    let k2 = this.values.jobs[index].cards[i2].key;
                                    cards_errors[k2] = e2;
                                }
                            });
                        }
                        // Finally, we assign the new cards errors (with the keys mapped) to this job's errors array.
                        this.jobs_errors[key].cards = cards_errors;
                    }
                });
            },
            deep: true
        }
    }
}
</script>
