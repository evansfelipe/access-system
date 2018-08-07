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
            <!-- First card tab -->
            <tab-item :active="tab === 3" @click.native="tab = 3" :has-errors="step_validated.first_card" icon="fas fa-id-card">
                Tarjeta
            </tab-item>
            <!-- Documentation tab -->
            <tab-item :active="tab === 4" @click.native="tab = 4" :has-errors="step_validated.documentation" icon="fas fa-file-alt">
                Documentación
            </tab-item>
        </ul>
        <!-- Card -->
        <creation-wrapper   :updating="this.$store.getters.person.updating" :values="values" :route="route" 
                            @saveSuccess="saveSuccess" @saveFailed="saveFailed" @cancel="cancel">
            <!-- Personal information form -->
            <pc-personal-information v-show="tab === 0" ref="personal_information" :errors="errors.personal_information" :values="values.personal_information"/>
            <!-- Working information form -->
            <pc-working-information v-show="tab === 1" ref="working_information" :errors="errors.working_information" :values="values.working_information"/>
            <!-- Assign vehicles form -->
            <pc-assign-vehicles v-show="tab === 2" ref="assign_vehicles" :companyname="company_name"
                                :companyid="parseInt(values.working_information.company_id)"/>
            <!-- First card form -->
            <pc-first-card  v-show="tab === 3" ref="first_card" :errors="errors.first_card" :values="values.first_card"
                            :fullname="full_name" :companyname="company_name"/>
            <!-- Documentation form -->
            <div v-show="tab === 4">Documentación</div>
        </creation-wrapper>
    </div>
</template>

<script>
    export default {
        components: {
            'pc-personal-information': require('./partials/PersonalInformation.vue'),
            'pc-working-information': require('./partials/WorkingInformation.vue'),
            'pc-assign-vehicles': require('./partials/AssignVehicles.vue'),
            'pc-first-card': require('./partials/FirstCard.vue'),
        },
        data: function() {
            return {
                tab: 0,
                first_save: false,
                errors: {
                    personal_information: {},
                    working_information:  {},
                    assign_vehicles:      {},
                    first_card:           {},
                    documentation:        {}
                }
            };
        },
        mounted() {
            let callback = (values, properties_path) => {
                this.$store.commit('updateModel', { which: 'person', properties_path: properties_path, value: values });
            }
            // Listens to the children component values changes.
            this.$on('personal-information-values', values => callback(values, 'values.personal_information'));
            this.$on('working-information-values',  values => callback(values, 'values.working_information'));
            this.$on('assign-vehicles-values',      values => callback(values, 'values.assign_vehicles'));
            this.$on('first-card-values',           values => callback(values, 'values.first_card'));
            this.$on('documentation-values',        values => callback(values, 'values.documentation'));
        },
        computed: {
            /**
             * Concatenates the person's last name with the person name. If some one of those values isn't seted, then puts an 'x'.
             */
            full_name: function() {
                return (this.values.personal_information.last_name || 'x') + ', ' + (this.values.personal_information.name || 'x');
            },
            /**
             * Company's name associated with the company id stored in the component data.
             */
            company_name: function() {
                let ret = '';
                if(this.values.working_information.company_id && !this.$store.getters.companies.updating) {
                    let val = this.$store.getters.companies.list.filter(company => company.id == this.values.working_information.company_id);
                    return val.length > 0 ? val[0].name : '-';
                }
                return ret;
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
            step_validated: function() {
                return {
                    personal_information: !this.first_save ? null : Object.keys(this.errors.personal_information).length > 0 ? false : true,
                    working_information:  !this.first_save ? null : Object.keys(this.errors.working_information).length > 0 ? false : true,
                    assign_vehicles:      !this.first_save ? null : Object.keys(this.errors.assign_vehicles).length > 0 ? false : true,
                    first_card:           !this.first_save ? null : Object.keys(this.errors.first_card).length > 0 ? false : true,
                    documentation:        !this.first_save ? null : Object.keys(this.errors.documentation).length > 0 ? false : true
                }
            }
        },
        methods: {
            saveSuccess: function(id) {
                this.$router.push(`/people/show/${id}`);
                this.$store.dispatch('addNotification', {type: 'success', message: `Persona ${this.id ? 'editada' : 'creada'} exitosamente.`});
                this.$store.commit('resetModel', 'person');
                this.first_save = true;
            },
            saveFailed: function(errors) {
                this.errors = errors;
                this.first_save = true;
            },
            cancel: function() {
                this.$router.go(-1);
                this.$store.commit('resetModel', 'person');
            },
        }
    }
</script>
