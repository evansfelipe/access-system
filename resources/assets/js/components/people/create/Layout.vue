<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- Personal information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.personal_information" icon="fas fa-user">
                Información personal
            </tab-item>
            <!-- Working information tab -->
            <tab-item :active="tab === 1" @click.native="tab = 1" :has-errors="step_validated.working_information" icon="fas fa-briefcase">
                Información laboral
            </tab-item>
            <!-- Assign vehicles tab -->
            <tab-item :active="tab === 2" @click.native="tab = 2" :has-errors="step_validated.assign_vehicles" icon="fas fa-car">
                Asignar vehículos
            </tab-item>
            <!-- Documentation tab -->
            <tab-item :active="tab === 3" @click.native="tab = 3" :has-errors="step_validated.documentation" icon="fas fa-file-alt">
                Documentación
            </tab-item>
        </ul>
        <!-- Card -->
        <creation-wrapper   :updating="updating" :values="values" :route="route" 
                            @saveSuccess="saveSuccess" @saveFailed="saveFailed" @cancel="cancel"
        >
            <personal-information v-show="tab === 0" :errors="personal_information_errors" :values="values.personal_information"/>
            <working-information  v-show="tab === 1" :errors="working_information_errors"  :values="values.working_information"/>
            <assign-vehicles      v-show="tab === 2" :assigned-companies="assigned_companies"/>
            <documentation        v-show="tab === 3" :errors="documentation_errors"/>
        </creation-wrapper>
    </div>
</template>

<script>
    export default {
        components: {
            'assign-vehicles':      require('./partials/AssignVehicles.vue'),
            'personal-information': require('./partials/PersonalInformation.vue'),
            'documentation':        require('./partials/Documentation/Layout.vue'),
            'working-information':  require('./partials/WorkingInformation/Layout.vue'),
        },
        data: function() {
            return {
                tab: 0,
                first_save: false,
                errors: [],
                step_validated: {
                    personal_information: null,
                    working_information:  null,
                    assign_vehicles:      null,
                    first_card:           null,
                    documentation:        null  
                }
            };
        },
        computed: {
            updating: function() {
                return this.$store.getters.person.updating;
            },
            assigned_companies: function() {
                let ids = [];
                this.values.working_information.jobs.forEach(job => {
                    if(job.company_id) ids.push(parseInt(job.company_id));
                });
                return ids;
            },
            id: function() {
                return this.$store.getters.person.id;
            },   
            values: function() {
                return this.$store.getters.person.values;
            },
            route: function() {
                return {
                    method: this.id ? 'put' : 'post',
                    url:    this.id ? `/people/${this.id}` : '/people'
                }
            },
            personal_information_errors: function() {
                return this.errors['personal_information'] ? this.errors['personal_information'] : []; 
            },
            working_information_errors: function() {
                return this.errors['working_information'] ? this.errors['working_information'] : []; 
            },
            documentation_errors: function() {
                return this.errors['documentation'] ? this.errors['documentation'] : [];
            }
        },
        methods: {
            saveSuccess: function(id) {
                this.$router.push(`/people/show/${id}`);
                this.$store.dispatch('addNotification', {type: 'success', message: `Persona ${this.id ? 'editada' : 'creada'} exitosamente.`});
                this.$store.commit('resetModel', 'person');
                this.first_save = true;
            },
            saveFailed: function({errors, step_validated}) {
                this.errors = errors;
                this.step_validated = step_validated;
                this.first_save = true;
            },
            cancel: function() {
                this.$router.go(-1);
                this.$store.commit('resetModel', 'person');
            },
        }
    }
</script>
