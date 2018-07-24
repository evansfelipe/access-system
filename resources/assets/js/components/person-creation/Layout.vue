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

    div.loading-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0; left: 0; bottom: 0; right: 0;
        background-color: rgba(80,80,80,.75);
        color: black;
        z-index: 2;
    }
</style>

<template>
    <div>
        <div v-if="saving" class="loading-panel text-center">
            <i class="fas fa-spinner fa-pulse fa-4x"></i>
        </div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 0 ? 'active' : ' ')" @click="active_tab = 0">
                    <i class="fas fa-user"></i> Información personal
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 1 ? 'active' : '')" @click="active_tab = 1">
                    <i class="fas fa-briefcase"></i> Información laboral
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 2 ? 'active' : '')" @click="active_tab = 2">
                    <i class="fas fa-car"></i> Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (active_tab === 3 ? 'active' : '')" @click="active_tab = 3">
                    <i class="fas fa-id-card"></i> Tarjeta
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
                                    :companyid="shared_information.company_id"
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
                step_saving: {
                    personal_information: false,
                    working_information: false,
                    assign_vehicles: false,
                    first_card: false,
                },
                step_results: {
                    personal_information: false,
                    working_information: false,
                    assign_vehicles: false,
                    first_card: false,
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
                this.step_results.personal_information = val;
                this.step_saving.personal_information = false;
            });
            this.$on('working-information-saved', val => {
                this.step_results.working_information = val;
                this.step_saving.working_information = false;
            });
            this.$on('assign-vehicles-saved', val => {
                this.step_results.assign_vehicles = val;
                this.step_saving.assign_vehicles = false;
            });
            this.$on('first-card-saved', val => {
                this.step_results.first_card = val;
                this.step_saving.first_card = false;
            });
        },
        methods: {
            save: function() {
                window.scrollTo(0,0);
                this.step_saving.personal_information = true;
                this.step_saving.working_information = true;
                this.step_saving.assign_vehicles = true;
                this.step_saving.first_card = true;

                this.$refs.personal_information.save();
                this.$refs.working_information.save();
                this.$refs.assign_vehicles.save();
                this.$refs.first_card.save();
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
                return this.step_saving.personal_information && this.step_saving.working_information && this.step_saving.assign_vehicles && this.step_saving.first_card;
            },
            store_completed: function() {
                return this.step_results.personal_information && this.step_results.working_information && this.step_results.assign_vehicles && this.step_results.first_card;
            }      
        },        
        watch: {
        }
    }
</script>
