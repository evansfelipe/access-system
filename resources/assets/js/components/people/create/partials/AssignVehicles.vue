<style lang="scss" scoped>    
    .dropdown-toggle::after {
        display:none;
    }

    .btn-link {
        color: grey;
        &:hover {
            text-decoration: none;
        }
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.vehicles.updating" message="Cargando..."/>
        <template v-else>
            <!-- Options buttons -->
            <div class="row text-center mb-3">
                <div class="col-11">
                    <div class="row">
                        <!-- Company vehicles list button -->
                        <div class="col-4">
                            <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'company' ? 'outline-unique btn-static':'link')"
                                    @click="selected_list = 'company'"
                                    :disabled="company_vehicles.length <= 0">
                                <span class="badge badge-dark ml-1">{{ company_vehicles.length }}</span> <abbreviation-text :text="companyname"/>
                            </button>
                        </div>
                        <!-- Others vehicles list button -->
                        <div class="col-4">
                            <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'others' ? 'outline-unique btn-static':'link')"
                                    @click="selected_list = 'others'"
                                    :disabled="others_vehicles.length <= 0">
                                <span class="badge badge-dark ml-1">{{ others_vehicles.length }}</span> Otras empresas
                            </button>
                        </div>
                        <!-- Selected vehicles list button -->
                        <div class="col-4">
                            <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'picked' ? 'outline-unique btn-static':'link')"
                                    @click="selected_list = 'picked'"
                                    :disabled="vehicles_picked.length <= 0">
                                <span class="badge badge-dark ml-1">{{ vehicles_picked.length }}</span> Seleccionados
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Extra options dropdown -->
                <div class="col-1 pl-0">
                    <div class="dropdown show">
                        <!-- Open/Close button -->
                        <a class="btn btn-link btn-block dropdown-toggle" role="button" id="more-options" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        title="Más opciones">
                            <i class="fas fa-bars fa-lg"></i>
                        </a>
                        <!-- Options -->
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="more-options">
                            <!-- New vehicle -->
                            <span class="dropdown-item">
                                <i class="fas centered fa-plus"></i> Agregar nuevo vehículo
                            </span>
                            <!-- Show or hide outdated vehicles -->
                            <span class="dropdown-item" @click="show_outdated = !show_outdated">
                                <i :class="'far centered fa-eye' + (show_outdated ? '-slash':'')"></i>
                                {{ show_outdated ? 'Ocultar' : 'Mostrar' }} vehículos vencidos
                            </span>
                            <span class="dropdown-item" @click="unpickAll">
                                <i class="fas centered fa-ban"></i> Deseleccionar todos
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /Extra options dropdown -->
            </div>
            <!-- /Options buttons -->
            <!-- Vehicles list -->
            <hr>
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
            default: '-'
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
