<style scoped>
    .card {
        border-top: 0;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
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
        <div class="card card-default">
            <div class="card-body">
                <!-- Loading cover will be only be rendered when component is saving the data to the server -->
                <loading-cover v-if="saving.status" :message="saving.message"/>
                <!-- Personal information form -->
                <pc-personal-information v-show="tab === 0" ref="personal_information" :errors="errors.personal_information" :values="values.personal_information"/>
                <!-- Working information form -->
                <pc-working-information v-show="tab === 1" ref="working_information" :errors="errors.working_information" :values="values.working_information"
                                        :companies="companies_list" :activities="activities_list"/>
                <!-- Assign vehicles form -->
                <pc-assign-vehicles v-show="tab === 2" ref="assign_vehicles" 
                                    :vehicles="vehicles_list" :companyname="company_name"
                                    :companyid="parseInt(values.working_information.company_id)"/>
                <!-- First card form -->
                <pc-first-card  v-show="tab === 3" ref="first_card" :errors="errors.first_card" :values="values.first_card"
                                :fullname="full_name" :companyname="company_name"/>
                <!-- Documentation form -->
                <div v-show="tab === 4">
                    Documentación
                </div>
                <!-- Buttons -->
                <hr>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-outline-danger btn-sm" href="/people">Cancelar</a>
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
        props: ['person', 'companies', 'activities', 'vehicles'],
        data: function() {
            return {
                tab: 0,
                vehicles_list:   JSON.parse(this.vehicles),
                companies_list:  JSON.parse(this.companies),
                activities_list: JSON.parse(this.activities),
                saving: {
                    status: false,
                    message: ''
                },
                step_validated: {
                    personal_information: null,
                    working_information:  null,
                    assign_vehicles:      null,
                    first_card:           null,
                    documentation:        null
                },
                values: {
                    
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
        beforeMount() {
            if(this.person != null){
                let person_info = JSON.parse(this.person);
                this.values = {
                    personal_information: JSON.parse(person_info.personal_information),
                    working_information: JSON.parse(person_info.working_information),
                    assign_vehicles: JSON.parse(person_info.assign_vehicles),
                    first_card: JSON.parse(person_info.first_card),
                    documentation: JSON.parse(person_info.documentation),
                }
            }
            else{
                this.values = {
                    personal_information: {
                        last_name: '',
                        name: '',
                        document_type: '',
                        document_number: '',                
                        cuil: '',
                        birthday: '',
                        sex: '',
                        blood_type: '',
                        pna: '',
                        email: '',
                        home_phone: '',
                        mobile_phone: '',
                        fax: '',
                        street: '',
                        apartment: '',
                        cp: '',
                        country: '',
                        province: '',
                        city: ''
                    },
                    working_information: {
                        company_id: '',
                        activity_id: '',
                        art: '',
                        pbip: ''
                    },
                    assign_vehicles: {
                    },
                    first_card: {
                        number: '',
                        risk: '',
                        from: '',
                        until: ''
                    },
                    documentation: {
                    }
                }
            }
            
        },
        mounted() {
            // Listens to the children component values changes.
            this.$on('personal-information-values', values => this.values.personal_information = values);
            this.$on('working-information-values',  values => this.values.working_information  = values);
            this.$on('assign-vehicles-values',      values => this.values.assign_vehicles      = values);
            this.$on('first-card-values',           values => this.values.first_card           = values);
            this.$on('documentation-values',        values => this.values.documentation        = values);
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
                return this.values.working_information.company_id ? this.companies_list.filter(company => company.id == this.values.working_information.company_id)[0].name : '-';
            }
        },
        methods: {
            /**
             * Tries to save the new person on the server. If the saving is successful, then redirects the user.
             * Otherwise, shows the validation errors.
             */
            save: function() {
                window.scrollTo(0,0);
                // Until the axios request is performed, then the view will be locked and showing a loading message.
                this.saving = { status: true, message: "Guardando..." }
                // Performs the request whit the merged data of each steps.
                let thenCallback = response => {
                    this.saving.message = "Redirigiendo...";
                    window.location.href = response.data;
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
                    this.saving.status = false;
                };
                let data = { 
                    ...this.values.personal_information,
                    ...this.values.working_information,
                    ...this.values.assign_vehicles,
                    ...this.values.first_card
                };
                if(this.person == null){
                    axios.post('/people', data) 
                        .then(response => thenCallback(response))
                        .catch(response => catchCallback(response));
                }
                else{
                    axios.put(JSON.parse(this.person).update_url, data)
                        .then(response => thenCallback(response))
                        .catch(response => catchCallback(response));
                }
            }
        }
    }
</script>
