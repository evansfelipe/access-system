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
            <tab-item :active="active_tab === 0" @click.native="active_tab = 0" :has-errors="steps.personal_information.saved" icon="fas fa-user">
                Información personal
            </tab-item>
            <!-- Working information tab -->
            <tab-item :active="active_tab === 1" @click.native="active_tab = 1" :has-errors="steps.working_information.saved" icon="fas fa-briefcase">
                Información laboral
            </tab-item>
            <!-- Assign vehicles tab -->
            <tab-item :active="active_tab === 2" @click.native="active_tab = 2" :has-errors="steps.assign_vehicles.saved" icon="fas fa-car">
                Asignar vehículos
            </tab-item>
            <!-- First card tab -->
            <tab-item :active="active_tab === 3" @click.native="active_tab = 3" :has-errors="steps.first_card.saved" icon="fas fa-id-card">
                Tarjeta
            </tab-item>
            <!-- Documentation tab -->
            <tab-item :active="active_tab === 4" @click.native="active_tab = 4" :has-errors="steps.documentation.saved" icon="fas fa-file-alt">
                Documentación
            </tab-item>
        </ul>
        <!-- /Tabs -->
        <!-- Forms -->
        <div class="card card-default">
            <div class="card-body">
                <!-- Loading cover will be only be rendered when component is saving the data to the server -->
                <loading-cover v-if="saving"/>
                <!-- Personal information form -->
                <pc-personal-information v-show="active_tab === 0" ref="personal_information"/>
                <!-- Working information form -->
                <pc-working-information v-show="active_tab === 1" ref="working_information"
                                        :companies="companies_list" :activities="activities_list"/>
                <!-- Assign vehicles form -->
                <pc-assign-vehicles v-show="active_tab === 2" ref="assign_vehicles" 
                                    :vehicles="vehicles_list" :companyname="company_name"
                                    :companyid="parseInt(shared_information.company.id)"/>
                <!-- First card form -->
                <pc-first-card  v-show="active_tab === 3" ref="first_card"
                                :fullname="full_name" :companyname="company_name"/>
                <!-- Documentation form -->
                <div v-show="active_tab === 4">
                    Documentación
                </div>
                <!-- Buttons -->
                <hr>
                <div class="form-row">
                    <div class="col">
                        <a class="btn btn-outline-danger btn-sm" href="/person-creation/cancel">Cancelar</a>
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
        props: ['companies', 'activities', 'vehicles'],
        data: function() {
            return {
                active_tab: 0,
                vehicles_list:   JSON.parse(this.vehicles),
                companies_list:  JSON.parse(this.companies),
                activities_list: JSON.parse(this.activities),
                shared_information: {
                    person: {
                        name: '',
                        last_name: ''
                    },
                    company: {
                        id: ''
                    },
                },
                steps: {
                    personal_information: {
                        saved:     null,
                        is_saving: false,
                    },
                    working_information: {
                        saved:     null,
                        is_saving: false,
                    },
                    assign_vehicles: {
                        saved:     null,
                        is_saving: false,
                    },
                    first_card: {
                        saved:     null,
                        is_saving: false,
                    },
                    documentation: {
                        saved:     null,
                        is_saving: false,
                    }
                }
            };
        },
        mounted() {
            // Listening for shared information changes on children.
            this.$on('company-id-changed', val => this.shared_information.company.id = val);
            this.$on('name-changed',       val => this.shared_information.person.name = val);
            this.$on('last-name-changed',  val => this.shared_information.person.last_name = val);
            // Listening each step save's result
            this.$on('personal-information-saved', val => {
                this.steps.personal_information.is_saving = false;
                this.steps.personal_information.saved = val;
                if(val) {
                    this.steps.working_information.is_saving = true;
                    this.$refs.working_information.save();
                }
                else{
                    this.active_tab = 0;
                }
            });
            this.$on('working-information-saved', val => {
                this.steps.working_information.is_saving = false;
                this.steps.working_information.saved = val;
                if(val) {
                    this.steps.assign_vehicles.is_saving = true;
                    this.$refs.assign_vehicles.save();
                }
                else{
                    this.active_tab = 1;
                }
            });
            this.$on('assign-vehicles-saved', val => {
                this.steps.assign_vehicles.is_saving = false;
                this.steps.assign_vehicles.saved = val;
                if(val) {
                    this.steps.first_card.is_saving = true;
                    this.$refs.first_card.save();
                }
                else{
                    this.active_tab = 2;
                }
            });
            this.$on('first-card-saved', val => {
                this.steps.first_card.is_saving = false;
                this.steps.first_card.saved = val;
                if(!val) {
                    this.active_tab = 3;
                }
            });
        },
        methods: {
            save: function() {
                window.scrollTo(0,0);
                this.steps.personal_information.is_saving = true;
                this.$refs.personal_information.save();
            }
        },
        computed: {
            /**
             * Concatenates the person's last name with the person name. If some one of those values isn't seted, then puts an 'x'.
             */
            full_name: function() {
                return (this.shared_information.person.last_name || 'x') + ', ' + (this.shared_information.person.name || 'x');
            },
            /**
             * Company's name associated with the company id stored in the component data.
             */
            company_name: function() {
                return this.shared_information.company.id ? this.companies_list.filter(company => company.id == this.shared_information.company.id)[0].name : '-';
            },
            /**
             * True if at least one of the steps is still being saved.
             */
            saving: function() {
                return this.steps.personal_information.is_saving || this.steps.working_information.is_saving || this.steps.assign_vehicles.is_saving || this.steps.first_card.is_saving;
            },
            /**
             * True if each step has been saved successfully.
             */
            saved: function() {
                return this.steps.personal_information.saved && this.steps.working_information.saved && this.steps.assign_vehicles.saved && this.steps.first_card.saved;
            }
        },        
        watch: {
            saved: function() {
                if(this.saved) {
                    alert("Guardando")
                // console.log("Saved");
                // axios.get('/person-creation/store')
                //     .then(response => {
                //         window.location.href = response.data;
                //     })
                //     .catch(response => {
                //         console.log(response);
                //     })
                }
            }
        }
    }
</script>
