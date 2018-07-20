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
                            <input v-if="vehicle.picked" :value="vehicle.id"
                                   type="hidden" name="vehicles_id[]" 
                            >
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: { vehicles: { required: true }},
    data: function() {
        let parsed = JSON.parse(this.vehicles);
        return {
            unfilteredVehiclesList: parsed,
            vehiclesList: parsed,
            search: "",
        };
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
