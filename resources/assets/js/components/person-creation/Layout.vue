<style scoped>
    .nav-tabs > .nav-item {
        cursor: pointer;
    }

    .nav-tabs > .nav-item > a.active {
        background-color: white;
        border-bottom-color: white;
        cursor: auto;
        color: black  !important;
    }

    a.inactive {
        color: grey !important;
    }

    .nav-item + .nav-item {
        margin-left: 1px;
    }

    .card {
        border-top: 0;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }

    table {
        width: 100%;
    }

    .strong {
        font-weight: bold;
    }
</style>

<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 0 ? 'active' : 'inactive')" @click="changeTab(0)">
                    <i class="fas fa-user"></i>
                    Información personal
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 1 ? 'active' : 'inactive')" @click="changeTab(1)">
                    <i class="fas fa-briefcase"></i>
                    Información laboral
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 2 ? 'active' : 'inactive')" @click="changeTab(2)">
                    <i class="fas fa-car"></i> 
                    Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 3 ? 'active' : 'inactive')" @click="changeTab(3)">
                    <i class="fas fa-id-card"></i>
                    Tarjeta
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 4 ? 'active' : 'inactive')" @click="changeTab(4)">
                    <i class="fas fa-file-alt"></i>
                    Documentación
                </a>
            </li>
        </ul>
        <!-- Content -->
        <div class="card card-default">
            <div class="card-body">
                <!-- Content for the tab number 0 -->
                <div :class="tab_number === 0 ? '' : 'd-none'">
                    <pc-personal-information ref="personal_information"></pc-personal-information>
                </div>
                <!-- Content for the tab number 1 -->
                <div :class="tab_number === 1 ? '' : 'd-none'">
                    <pc-working-information ref="working_information"
                                            :companies="companiesList"
                                            :activities="activitiesList"
                                            >
                    </pc-working-information>
                </div>
                <!-- Content for the tab number 2 -->
                <div :class="tab_number === 2 ? '' : 'd-none'">
                    <pc-assign-vehicles ref="assign_vehicles"
                                        :vehicles="vehiclesList"
                                        :companyid="company_id"
                                        :companyname="company_name">
                    </pc-assign-vehicles>
                </div>
                <!-- Content for the tab number 3 -->
                <div :class="tab_number === 3 ? '' : 'd-none'">
                    <pc-first-card  ref="first_card"
                                    :fullname="full_name"
                                    :companyname="company_name">
                    </pc-first-card>
                </div>
                <!-- Content for the tab number 4 -->
                <div :class="tab_number === 4 ? '' : 'd-none'">
                    Documentación
                </div>

                <hr>
                <div class="form-row">
                    <button type="submit" class="btn btn-outline-success btn-sm" @click="save">Guardar</button>
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
                tab_number: 0,
                companiesList: JSON.parse(this.companies),
                activitiesList: JSON.parse(this.activities),
                vehiclesList: JSON.parse(this.vehicles),
                person_last_name: '',
                person_name: '',
                company_id: '',
            };
        },
        mounted() {
            this.$on('last-name-changed', val => {
                this.person_last_name = val;
            });
            this.$on('name-changed', val => {
                this.person_name = val;
            });
            this.$on('company-id-changed', val => {
                this.company_id = val;
            });

            this.$on('personal-information-saved', val => {
                if(val) {
                    this.$refs.working_information.save();
                }
                else {
                    this.tab_number = 0;
                }
            });
            this.$on('working-information-saved', val => {
                if(val) {
                    this.$refs.assign_vehicles.save();
                }
                else {
                    this.tab_number = 1;
                }
            });
            this.$on('assign-vehicles-saved', val => {
                if(val) {
                    this.$refs.first_card.save();
                }
                else {
                    this.tab_number = 2;
                }
            });
            this.$on('first-card-saved', val => {
                if(val) {
                    axios.get('/person-creation/store')
                    .then(response => {
                        console.log(response);
                    })
                    .catch(response => {
                        console.log(response);
                    })
                }
                else {
                    this.tab_number = 3;
                }
            });
        },
        methods: {
            changeTab: function(tab_number) {
                this.tab_number = tab_number;
            },
            save: function() {
                this.$refs.personal_information.save();
                // this.$refs.working_information.save();
                // this.$refs.assign_vehicles.save();
                // this.$refs.first_card.save();
            }
        },
        computed: {
            full_name: function() {
                return this.person_last_name != '' && this.person_name != '' ? this.person_last_name + ', ' + this.person_name : 'x';
            },
            company_name: function() {
                return this.company_id ? this.companiesList.filter(company => company.id == this.company_id)[0].name : 'x';
            }         
        },        
        watch: {
            
        }
    }
</script>
