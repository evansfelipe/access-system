<style lang="scss" scoped>
    table {
        display: flex;
        flex-flow: column;
        height: 100%; width: 100%;
        & > thead {
            flex: 0 0 auto;
            width: calc(100% - 5px);
            & > tr {
                border: 0;
                border-bottom: 1px solid grey;
                & > th { border: 0 }
                & > th > i { color: transparent }
            }
        }
        & > thead > tr > th:first-of-type, & > tbody > tr > td:first-of-type {
            width: 5%;
            text-align: center;
        }

        & > thead, & > tbody > tr {
            display: table;
            table-layout: fixed;
        }

        & > tbody {
            flex: 1 1 auto;
            display: block;
            overflow-y: scroll;
            & > tr {
                width: 100%;
            }
            & > tr > td > i.selected-item {
                color: #3F729B;
            }
            & > tr:hover {
                cursor: pointer;
                background-color: rgb(250, 250, 250);
            }
            & > tr > td {
                border: 0;
                border-bottom: 1px solid whitesmoke;
                padding: 8px;
            } 
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
        <div class="row text-center mb-3">
            <!-- Company vehicles list button -->
            <div class="col-4">
                <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'company' ? 'outline-unique btn-static':'link')"
                        @click="selected_list = 'company'"
                        :disabled="company_vehicles.length <= 0">
                    <abbreviation-text :text="companyname"/> <span class="badge badge-dark ml-1">{{ company_vehicles.length }}</span>
                </button>
            </div>
            <!-- Others vehicle list button -->
            <div class="col-4">
                <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'others' ? 'outline-unique btn-static':'link')"
                        @click="selected_list = 'others'"
                        :disabled="others_vehicles.length <= 0">
                    Otras empresas <span class="badge badge-dark ml-1">{{ others_vehicles.length }}</span>
                </button>
            </div>
            <div class="col-3">
                <input type="text" class="form-control" v-model="search" placeholder="Búsqueda">
            </div>
            <div class="col-1">
                <div class="dropdown show">
                    <a class="btn btn-link dropdown-toggle" role="button" id="more-options" 
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       title="Más opciones">
                        <i class="fas fa-bars fa-lg"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="more-options">
                        <span class="dropdown-item" href="#">
                            <i class="fas fa-plus"></i> Agregar nuevo vehículo
                        </span>
                        <span class="dropdown-item" @click="show_outdated = !show_outdated">
                            <i v-if="show_outdated" class="far fa-eye-slash"></i>
                            <i v-else class="far fa-eye"></i>
                            {{ show_outdated ? 'Ocultar' : 'Mostrar' }} vehículos vencidos
                        </span>
                        <span class="dropdown-item" href="#" @click="unpickAll">
                            <i class="fas fa-ban"></i> Deseleccionar todos
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <!-- Vehicles list -->
            <div class="col-12 table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-check-square"></i></th>
                            <th>Patente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(vehicle, key) in vehicles_list" :key="key" @click="vehicle.picked = !vehicle.picked">
                            <td><i v-if="vehicle.picked" class="fas fa-check-square selected-item"></i></td>
                            <td>{{ vehicle.plate  }}</td>
                            <td>{{ vehicle.brand  }}</td>
                            <td>{{ vehicle.model  }}</td>
                            <td>{{ vehicle.year   }}</td>
                            <td>{{ vehicle.colour }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col text-center font-italic">
                <br>
                <span class="badge badge-light">{{ selected_vehicles_count }} seleccionados</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: { 
        vehicles: { 
            required: true,
            type: Array 
        },
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
            unfiltered_vehicles: [],
            others_vehicles: [],
            company_vehicles: [],
            search: "",
            selected_list: "",
            show_outdated: false
        };
    },
    mounted() {
        this.splitLists(this.company_id);
    },
    methods: {
        splitLists(company_id) {
            this.others_vehicles  = this.vehicles.filter(vehicle => vehicle.company_id !== company_id);
            this.company_vehicles = this.vehicles.filter(vehicle => vehicle.company_id === company_id);
            this.selected_list    = this.company_vehicles.length > 0 ? "company" : "others";
        },
        unpickAll() {
            if(confirm('Está seguro?')) {
                this.lists_combined.forEach(element => element.picked = false);
            }
        },
        save: function() {
            // Creates the object to send to the server.
            let data = { vehicles_id: [] }
            // Adds each vehicle id that has been picked.
            this.lists_combined.forEach(element => {
                if(element.picked) 
                    data.vehicles_id.push(element.id);
            });
            // Posts the data to the server and waits for the response.
            axios.post('person-creation/assign-vehicles', data)
                .then(response => {
                    this.errors = {};
                    this.$parent.$emit('assign-vehicles-saved', true);
                })
                .catch(error => {
                    console.log("Assign Vehicles", error.response.data.errors);
                    this.$parent.$emit('assign-vehicles-saved', false);
                });
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
        }
    },
    watch: {
        search: function(search) {
            search = search.toUpperCase();
            String.prototype.matches = function(other) { return this.toUpperCase().includes(other)};
            this.vehicles_list = this.unfiltered_vehicles.filter(({plate, brand, model, year, colour}) => {
                return colour.matches(search) || plate.matches(search) || brand.matches(search) ||
                       model.matches(search)  || year.toString().matches(search);
            });
        },
        selected_list: function(value) {
            this.unfiltered_vehicles = value === 'company' ? this.company_vehicles : this.others_vehicles;
            this.vehicles_list = this.unfiltered_vehicles;
            this.search = "";
        },
        companyid: function(value) {
            this.splitLists(value);
            this.unfiltered_vehicles = this.company_vehicles;
            this.vehicles_list = this.unfiltered_vehicles;
            this.search = "";
        }
    }
}
</script>
