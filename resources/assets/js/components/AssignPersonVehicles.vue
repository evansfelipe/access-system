<style lang="scss" scoped>
    table {
        width: 100%;
        & > thead, tbody, tr, td, th { display: block }

        & tr {
            padding:5px 5px;
            &:after {
                content: ' ';
                display: block;
                visibility: hidden;
                clear: both;
            }
        }
        & > thead {
            width: 98.5%;
            & > tr {
                height: 30px;
            }
        }
        & > tbody {
            height: 45vh;
            overflow-y: auto;
            & > tr {
                cursor:pointer;
                &:hover { background:#f1f7fc }
                &.vehicle-picked {
                    background:#3F729B;
                    color:white;
                    &:hover { background:#3F729B }
                }
            }
        }

    }
    tbody td, thead th {
        width: 19.2%;
        float: left;
    }
</style>

<template>
    <div>
        <div v-if="companyVehicles.length > 0" class="row text-center" style="margin-bottom: 10px">
            <div class="col-6 offset-md-2 col-md-4">
                <button type="button" class="btn btn-primary btn-block" @click="changeList('company')" id="companyButton">
                    {{ this.companyname }} <span class="badge badge-light">{{ companyVehicles.length }}</span>
                </button>
            </div>
            <div class="col-6 col-md-4">
                <button type="button" class="btn btn-secondary btn-block" @click="changeList('others')" id="othersButton">
                    Otros <span class="badge badge-light">{{ othersVehicles.length }}</span>
                </button>
            </div>
        </div>
        <!-- Search bar -->
        <div class="row">
            <div class="col-12 offset-md-2 col-md-8">
                <input type="text" class="form-control" v-model="search" placeholder="Búsqueda">
            </div>
        </div>
        <hr>
        <div class="row">
            <!-- Vehicles list -->
            <div class="col-12">
                <table>
                    <thead>
                        <tr>
                            <th>Patente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(vehicle, key) in vehiclesList" :key="key"
                            @click="vehicle.picked = !vehicle.picked"
                            :class="vehicle.picked ? 'vehicle-picked' : ''"
                        >
                            <td>{{ vehicle.plate  }}</td>
                            <td>{{ vehicle.brand  }}</td>
                            <td>{{ vehicle.model  }}</td>
                            <td>{{ vehicle.year   }}</td>
                            <td>{{ vehicle.colour }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-for="(vehicle, key) in othersVehicles" :key="key">
                    <input v-if="vehicle.picked" :value="vehicle.id"
                            type="hidden" name="vehicles_id[]" 
                    >
                </div>
                <div v-for="(vehicle, key) in companyVehicles" :key="key">
                    <input v-if="vehicle.picked" :value="vehicle.id"
                            type="hidden" name="vehicles_id[]" 
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: { vehicles: { required: true }, companyid: { required: false }, companyname: { required: false }},
    data: function() {
        let parsed = JSON.parse(this.vehicles);
        return {
            unfilteredVehiclesList: [],
            vehiclesList: [],
            othersVehicles:  parsed.filter(vehicle => vehicle.company_id != this.companyid),
            companyVehicles: parsed.filter(vehicle => vehicle.company_id == this.companyid),
            search: "",
        };
    },
    mounted() {
        if(this.companyVehicles.length > 0) {
            this.unfilteredVehiclesList = this.companyVehicles;
            this.vehiclesList = this.companyVehicles;
        }
        else {
            this.unfilteredVehiclesList = this.othersVehicles;
            this.vehiclesList = this.othersVehicles;
        }
    },
    methods: {
        changeList: function(listName) {
            let change = function(clicked, other) {
                document.getElementById(clicked).classList.remove('btn-secondary');
                document.getElementById(clicked).classList.add('btn-primary');

                document.getElementById(other).classList.remove('btn-primary');
                document.getElementById(other).classList.add('btn-secondary');
            }
            if(listName === 'company') {
                change('companyButton', 'othersButton');
                this.unfilteredVehiclesList = this.companyVehicles;
            }
            else {
                change('othersButton', 'companyButton');
                this.unfilteredVehiclesList = this.othersVehicles;
            }
            this.search = "";
            this.vehiclesList = this.unfilteredVehiclesList;
        }
    },
    watch: {
        search: function() {
            this.vehiclesList = this.unfilteredVehiclesList.filter(vehicle => {
                return  vehicle.plate.toUpperCase().includes(this.search.toUpperCase()) ||
                        vehicle.brand.toUpperCase().includes(this.search.toUpperCase()) ||
                        vehicle.model.toUpperCase().includes(this.search.toUpperCase()) ||
                        vehicle.year.toString().includes(this.search.toUpperCase())     ||
                        vehicle.colour.toUpperCase().includes(this.search.toUpperCase());
            });
        },
    }
}
</script>
