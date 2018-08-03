<style lang="scss" scoped>
    table {
        & > thead > tr > th:first-of-type, & > tbody > tr > td:first-of-type {
            width: 5%;
            text-align: center;
        }
    }

    div.table-container {
        height: 50vh;
    }
    
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
        <loading-cover v-if="this.$store.state.vehicles.updating" message="Cargando..."/>
        <template v-else>
            <!-- Options buttons -->
            <div class="row text-center mb-3">
                <!-- Company vehicles list button -->
                <div class="col-4">
                    <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'company' ? 'outline-unique btn-static':'link')"
                            @click="selected_list = 'company'"
                            :disabled="company_vehicles.length <= 0">
                        <abbreviation-text :text="companyname"/> <span class="badge badge-dark ml-1">{{ company_vehicles.length }}</span>
                    </button>
                </div>
                <!-- Others vehicles list button -->
                <div class="col-4">
                    <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'others' ? 'outline-unique btn-static':'link')"
                            @click="selected_list = 'others'"
                            :disabled="others_vehicles.length <= 0">
                        Otras empresas <span class="badge badge-dark ml-1">{{ others_vehicles.length }}</span>
                    </button>
                </div>
                <!-- Search input -->
                <div class="col-3">
                    <input type="text" class="form-control" v-model="search" placeholder="Búsqueda">
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
            <div class="row">
                <div class="col-12 table-container">
                    <custom-table
                        :columns="[ 
                            {name: 'plate', text: 'Patente'},
                            {name: 'brand', text: 'Marca'},
                            {name: 'model', text: 'Modelo'},
                            {name: 'year', text: 'Año'},
                            {name: 'colour', text: 'Color'},
                        ]"
                        :rows="vehicles_list"
                        :filter="{
                            strict: false,
                            conditions: {
                                all: this.search,
                            }
                        }"
                        maxHeight="53vh"
                        :pickable=true
                        @rowclicked="toggleVehicle"
                    />
                </div>
            </div>
            <!-- /Vehicles list -->
            <!-- Selected vehicles count -->
            <div class="row">
                <div class="col text-center">
                    <br>
                    <span class="badge badge-light font-italic">{{ selected_vehicles_count }} seleccionados</span>
                </div>
            </div>
            <!-- /Selected vehicles count -->
        </template>
    </div>
</template>

<script>
export default {
    props: {
        companyid: { 
            required: false,
            type: Number,
            default: -1
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
            unfiltered_company_vehicles: [],
            unfiltered_others_vehicles: [],
            others_vehicles: [],
            company_vehicles: [],
            search: "",
            selected_list: "",
            show_outdated: false
        };
    },
    beforeMount() {
        this.$store.dispatch('fetch', 'vehicles');
        this.splitLists(this.companyid);
    },
    methods: {
        splitLists(company_id) {
            this.unfiltered_others_vehicles  = this.vehicles.filter(vehicle => vehicle.company_id !== company_id);
            this.unfiltered_company_vehicles = this.vehicles.filter(vehicle => vehicle.company_id === company_id);
            this.company_vehicles = this.unfiltered_company_vehicles;
            this.others_vehicles = this.unfiltered_others_vehicles;
            if(this.company_vehicles.length > 0 || this.others_vehicles.length > 0)
                this.selected_list = this.company_vehicles.length > 0 ? "company" : "others";
        },
        unpickAll() {
            this.lists_combined.forEach(element => element.picked = false);
        },
        toggleVehicle(vehicle) {
            vehicle.picked = !vehicle.picked;
        }
    },
    computed: {
        selected_vehicles_count: function() {
            let count = 0;
            this.lists_combined.forEach(element => count += element.picked ? 1 : 0);
            return count;
        },
        lists_combined: function() {
            return this.company_vehicles.concat(this.others_vehicles);
        },
        vehicles: function() {
            return this.$store.state.vehicles.list;
        }
    },
    watch: {
        vehicles: function() {
            this.splitLists(this.companyid);
        },
        selected_list: function(value) {
            this.vehicles_list = value === 'company' ? this.company_vehicles : this.others_vehicles;
        },
        companyid: function(value) {
            
            this.splitLists(value);
        },
        lists_combined: {
            handler: function() {                
                let data = []
                this.lists_combined.forEach(element => {
                    if(element.picked) 
                        data.push(element.id);
                });
                this.$parent.$emit('assign-vehicles-values', {vehicles_id: data});
            },
            deep: true
        }
    }
}
</script>
