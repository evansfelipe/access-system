<style lang="scss" scoped>
    ul.nav-tabs > .nav-item {
        cursor: pointer;
        & + & { margin-left: 1px }
        & > a {
            color: grey;
            &.active {
                color: black;
                background-color: white;
                border-bottom-color: white;
            }
        }
    }

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
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 0 ? 'active' : ' ')" @click="active_tab = 0">
                    <i v-if="step_data.personal_information.saved === null" class="fas fa-user"></i>
                    <i v-if="step_data.personal_information.saved === true" class="far fa-check-circle text-success"></i>
                    <i v-if="step_data.personal_information.saved === false" class="far fa-times-circle text-danger"></i>
                    Información personal
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 1 ? 'active' : '')" @click="active_tab = 1">
                    <i v-if="step_data.working_information.saved === null" class="fas fa-briefcase"></i>
                    <i v-if="step_data.working_information.saved === true" class="far fa-check-circle text-success"></i>
                    <i v-if="step_data.working_information.saved === false" class="far fa-times-circle text-danger"></i>
                    Información laboral
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 2 ? 'active' : '')" @click="active_tab = 2">
                    <i v-if="step_data.assign_vehicles.saved === null" class="fas fa-car"></i>
                    <i v-if="step_data.assign_vehicles.saved === true" class="far fa-check-circle text-success"></i>
                    <i v-if="step_data.assign_vehicles.saved === false" class="far fa-times-circle text-danger"></i>
                    Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 3 ? 'active' : '')" @click="active_tab = 3">
                    <i v-if="step_data.first_card.saved === null" class="fas fa-id-card"></i>
                    <i v-if="step_data.first_card.saved === true" class="far fa-check-circle text-success"></i>
                    <i v-if="step_data.first_card.saved === false" class="far fa-times-circle text-danger"></i>
                    Tarjeta
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 4 ? 'active' : '')" @click="active_tab = 4">
                    <i class="fas fa-file-alt"></i> Documentación
                </a>
            </li>
        </ul>
        <!-- Content -->
        <div class="card card-default">
            <loading v-if="saving"/>

            <div class="card-body">
                <!-- Personal information form -->
                <pc-personal-information ref="personal_information" :class="active_tab === 0 ? '' : 'd-none'">
                </pc-personal-information>
                <!-- Working information form -->
                <pc-working-information ref="working_information" :class="active_tab === 1 ? '' : 'd-none'"
                                        :companies="companies_list"
                                        :activities="activities_list">
                </pc-working-information>
                <!-- Assign vehicles form -->
                <pc-assign-vehicles ref="assign_vehicles" :class="active_tab === 2 ? '' : 'd-none'"
                                    :vehicles="vehicles_list"
                                    :companyid="parseInt(shared_information.company_id)"
                                    :companyname="company_name">
                </pc-assign-vehicles>
                <!-- First card form -->
                <pc-first-card  ref="first_card" :class="active_tab === 3 ? '' : 'd-none'"
                                :fullname="full_name"
                                :companyname="company_name">
                </pc-first-card>
                <!-- Documentation form -->
                <div :class="active_tab === 4 ? '' : 'd-none'">
                    Documentación
                </div>

                <hr>
                <div class="form-row">
                    <div class="col-6">
                        <a class="btn btn-outline-danger btn-sm" href="/person-creation/cancel">Cancelar</a>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-outline-success btn-sm" @click="save">Guardar</button>
                    </div>
                </div>

            </div>
        </div>

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
                    person_last_name: '',
                    person_name: '',
                    company_id: '',
                },
                step_data: {
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
                    }
                }
            };
        },
        mounted() {
            // Listening for shared information changes on children.
            this.$on('company-id-changed', val => this.shared_information.company_id = val);
            this.$on('name-changed',       val => this.shared_information.person_name = val);
            this.$on('last-name-changed',  val => this.shared_information.person_last_name = val);
            // Listening each step save's result
            this.$on('personal-information-saved', val => {
                this.step_data.personal_information.is_saving = false;
                this.step_data.personal_information.saved = val;
                if(val) {
                    this.step_data.working_information.is_saving = true;
                    this.$refs.working_information.save();
                }
                else{
                    this.active_tab = 0;
                }
            });
            this.$on('working-information-saved', val => {
                this.step_data.working_information.is_saving = false;
                this.step_data.working_information.saved = val;
                if(val) {
                    this.step_data.assign_vehicles.is_saving = true;
                    this.$refs.assign_vehicles.save();
                }
                else{
                    this.active_tab = 1;
                }
            });
            this.$on('assign-vehicles-saved', val => {
                this.step_data.assign_vehicles.is_saving = false;
                this.step_data.assign_vehicles.saved = val;
                if(val) {
                    this.step_data.first_card.is_saving = true;
                    this.$refs.first_card.save();
                }
                else{
                    this.active_tab = 2;
                }
            });
            this.$on('first-card-saved', val => {
                this.step_data.first_card.is_saving = false;
                this.step_data.first_card.saved = val;
                if(!val) {
                    this.active_tab = 3;
                }
            });
        },
        methods: {
            save: function() {
                window.scrollTo(0,0);
                this.step_data.personal_information.is_saving = true;
                this.$refs.personal_information.save();
            }
        },
        computed: {
            full_name: function() {
                return (this.shared_information.person_last_name || 'x') + ', ' + (this.shared_information.person_name || 'x');
            },
            company_name: function() {
                return this.shared_information.company_id ? this.companies_list.filter(company => company.id == this.shared_information.company_id)[0].name : 'x';
            },
            saving: function() {
                return this.step_data.personal_information.is_saving || this.step_data.working_information.is_saving || this.step_data.assign_vehicles.is_saving || this.step_data.first_card.is_saving;
            },
            saved: function() {
                return this.step_data.personal_information.saved && this.step_data.working_information.saved && this.step_data.assign_vehicles.saved && this.step_data.first_card.saved;
            }
        },        
        watch: {
            saved: function() {
                if(this.saved) {
                    console.log("Saved");
                    axios.get('/person-creation/store')
                        .then(response => {
                            window.location.href = response.data;
                        })
                        .catch(response => {
                            console.log(response);
                        })
                }
            }
        }
    }
</script>
