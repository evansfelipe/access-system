<style lang="scss" scoped>    
    .dropdown-toggle::after {
        display:none;
    }

    .btn-list, .btn-list:disabled {
        background-color: transparent;
        font-weight: bold;
        border: 1px solid grey;
        color: grey;
    }

    .btn-list.selected {
        border-color: #3F729B;
        color: #3F729B;
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.vehicles.updating" message="Cargando..."/>
        <template v-else>
            <!-- Options buttons -->
            <div class="row text-center mb-3">
                <div class="col-12">
                    <div class="row">
                        <!-- Company vehicles list button -->
                        <div class="col-4">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'company' ? 'selected' : ''}`"
                                    @click="selected_list = 'company'"
                                    :disabled="company_vehicles.length <= 0">
                                <span class="badge badge-dark ml-1">{{ company_vehicles.length }}</span> <abbreviation-text :text="companyname"/>
                            </button>
                        </div>
                        <!-- Others vehicles list button -->
                        <div class="col-4">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'others' ? 'selected' : ''}`"
                                    @click="selected_list = 'others'"
                                    :disabled="others_vehicles.length <= 0">
                                <span class="badge badge-dark ml-1">{{ others_vehicles.length }}</span> Otras empresas
                            </button>
                        </div>
                        <!-- Selected vehicles list button -->
                        <div class="col-4">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'picked' ? 'selected' : ''}`"
                                    @click="selected_list = 'picked'"
                                    :disabled="vehicles_picked.length <= 0">
                                <span class="badge badge-dark ml-1">{{ vehicles_picked.length }}</span> Seleccionados
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Options buttons -->
            <!-- Vehicles list -->
            <div class="row">
                <div class="col-12 table-container">
                    <custom-table
                        :columns="columns"
                        :rows="vehicles_list"
                        :pickable="{
                            active: true,
                            list: vehicles_picked
                        }"
                        @rowclicked="toggleVehicle"
                    />
                </div>
            </div>
            <!-- /Vehicles list -->
        </template>
    </div>
</template>

<script>
export default {
    props: {
        assignedcompanies: { 
            required: false,
            type: Array,
            default: []
        },
        companyname: { 
            required: false,
            type: String,
            default: 'Empresas asignadas'
        }
    },
    data: function() {
        return {
            vehicles_list: [],
            others_vehicles: [],
            company_vehicles: [],
            selected_list: "",
            show_outdated: false,
            columns: [ 
                {name: 'plate', text: 'Patente'},
                {name: 'brand', text: 'Marca'},
                {name: 'model', text: 'Modelo'},
                {name: 'year', text: 'Año'},
                {name: 'colour', text: 'Color'},
            ]
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'vehicles');
        this.splitLists();
        
    },
    computed: {
        vehicles: function() {
            return this.$store.getters.vehicles.list;
        },
        vehicles_picked: function() {
            return this.$store.getters.person.values.assign_vehicles.vehicles_id;
        },
        picked_list: function() {
            let list = [];
            this.vehicles_picked.forEach(vehicle_id => {
                list.push(this.$store.getters.vehicles.getById(vehicle_id));
            });
            return list;
        }
    }, 
    methods: {
        splitLists() {
            this.company_vehicles = [];
            this.others_vehicles = [];
            this.vehicles.forEach(vehicle => {
                if(this.assignedcompanies.includes(vehicle.company_id)) {
                    this.company_vehicles.push(vehicle);
                }
                else {
                    this.others_vehicles.push(vehicle);
                }
            });
            if(this.vehicles.length > 0) {
                if(this.company_vehicles.length > 0) {
                    this.selected_list = 'company';
                    this.vehicles_list = this.company_vehicles;
                }
                else {
                    this.selected_list = 'others';
                    this.vehicles_list = this.others_vehicles;
                }
            }
        },
        unpickAll() {
            this.$store.getters.vehicles.list.forEach(vehicle => this.$store.commit('pickVehicle', { id: vehicle.id, value: false }));
        },
        toggleVehicle(vehicle) {
            this.$store.commit('pickVehicle', vehicle.id);
        }
    },
    watch: {
        vehicles: function() {
            this.splitLists();
        },
        assignedcompanies: {
            handler: function() {
                this.splitLists();
            },
            deep: true
        },
        selected_list: function(value) {
            if( value === 'company' ) {
                this.vehicles_list = this.company_vehicles;
            }
            else if( value === 'others' ) {
                this.vehicles_list = this.others_vehicles;
            }
            else if( value === 'picked' ) {
                this.vehicles_list = this.picked_list;
            }
        }
    }
}
</script>
