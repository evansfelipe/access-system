<style scoped>
    .card-body {
        min-height: 30vh;
    }
</style>

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
        <!-- /Tabs -->
        <!-- Forms -->
        <div class="card card-default borderless-top-card ">
            <div class="card-body">
                <loading-cover v-if="this.$store.state.models.person.updating" message="Cargando..."/>
                <template v-else>
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
                    <div v-show="tab === 4">
                        Documentación
                    </div>
                </template>
                <!-- Buttons -->
                <hr>
                <div class="row">
                    <div class="col">
                        <confirmable-button btnclass="btn btn-outline-danger btn-sm" @confirmed="cancel">Cancelar</confirmable-button>
                        <button class="btn btn-outline-success btn-sm float-right" @click="save">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Forms -->
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
                person: {},
                step_validated: {
                    personal_information: null,
                    working_information:  null,
                    assign_vehicles:      null,
                    first_card:           null,
                    documentation:        null
                },
                errors: {
                    personal_information: {},
                    working_information: {},
                    assign_vehicles: {},
                    first_card: {},
                    documentation: {}
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
                if(this.values.working_information.company_id && !this.$store.state.companies.updating) {
                    let val = this.$store.state.companies.list.filter(company => company.id == this.values.working_information.company_id);
                    return val.length > 0 ? val[0].name : '-';
                }
                return ret;
            },
            values: function() {
                return this.$store.state.models.person.values;
            }
        },
        methods: {
            cancel: function() {
                this.$store.commit('resetModel', 'person');
                this.$router.go(-1);
            },
            /**
             * Tries to save the new person on the server. If the saving is successful, then redirects the user.
             * Otherwise, shows the validation errors.
             */
            save: function() {
                // Until the axios request is performed, then the view will be locked and showing a loading message.
                this.$store.commit('loading', { state: true, message: "Guardando..." })
                // Performs the request whit the merged data of each steps.
                let thenCallback = (response, text) => {
                    if(this.$store.state.debug) console.log('Person saved successfully');
                    this.$store.commit('loading', { state: false, message: "" });
                    this.$store.dispatch('addNotification', {type: 'success', message: `Persona ${text} exitosamente.`});
                    this.$router.push(`/people/show/${response.data.id}`);
                    this.$store.commit('resetModel', 'person');
                };
                let catchCallback = response => {
                    // Resets the errors of each component.
                    this.errors = {
                        personal_information: {},
                        working_information: {},
                        assign_vehicles: {},
                        first_card: {},
                        documentation: {}
                    };
                    // When the request starts, puts each step as validated. If there are errors, this status will be changed later.
                    this.step_validated = {
                        personal_information: true,
                        working_information: true,
                        assign_vehicles: true,
                        first_card: true,
                        documentation: true,
                    };
                    // Function that adds the error to the step error object.
                    let addError = (key, errors, step) => {
                        if(key in this.values[step]) {
                            this.errors[step][key] = errors[key];
                            this.step_validated[step] = false;
                        }
                    }
                    // Gets the errors from the response.
                    let errors = response.response.data.errors;
                    // Iterates each error and adds them to the error object.
                    Object.keys(errors).forEach(key => {
                        addError(key, errors, 'personal_information');
                        addError(key, errors, 'working_information');
                        addError(key, errors, 'assign_vehicles');
                        addError(key, errors, 'first_card');
                        addError(key, errors, 'documentation');
                    });
                    // Ends the loading status.
                    this.$store.dispatch('addNotification', {type: 'danger', message: 'Corrija los errores antes de continuar.'});
                    this.$store.commit('loading', { state: false, message: "" })
                };
                let data = { 
                    ...this.values.personal_information,
                    ...this.values.working_information,
                    ...this.values.assign_vehicles,
                    ...this.values.first_card
                };
                if(!this.values.id) {
                    if(this.$store.state.debug) console.log('Saving person on people.store');
                    axios.post('/people', data) 
                        .then(response => thenCallback(response, 'creada'))
                        .catch(response => catchCallback(response));
                }
                else {
                    if(this.$store.state.debug) console.log('Saving person on people.update');
                    axios.put(`/people/${this.values.id}`, data) 
                        .then(response => thenCallback(response, 'editada'))
                        .catch(response => catchCallback(response));
                }
            }
        }
    }
</script>
