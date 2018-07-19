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
        <!-- Vehicles list -->
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
                <tr v-for="(vehicle, key) in vehiclesList"
                    :id="key" :key="key" 
                    @click="toggleVehicle(key, vehicle)"
                    :class="vehicle.picked ? 'vehicle-picked' : ''"
                >
                    <td>{{ vehicle.plate }}</td>
                    <td>{{ vehicle.brand }}</td>
                    <td>{{ vehicle.model }}</td>
                    <td>{{ vehicle.year }}</td>
                    <td>{{ vehicle.colour }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div class="form-row">
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Cancelar</button>
            </div>
            <div class="col-6 text-right">
                <button type="button" class="btn btn-sm btn-outline-success" @click="submit">Siguiente <i class="fas fa-angle-double-right"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        vehicles: {
            required: true
        },
        method: {
            type: String,
            required: false,
            default: 'post'
        },
        path: {
            type: String,
            required: true
        }
    },
    data: function() {
        let parsed = JSON.parse(this.vehicles);
        return {
            unfilteredVehiclesList: parsed,
            vehiclesList: parsed,
            search: "",
        };
    },
    methods: {
        toggleVehicle: function(key, vehicle) {
            vehicle.picked = vehicle.picked ? !vehicle.picked : true;
            document.getElementById(key).classList.toggle('vehicle-picked');
        },
        submit: function() {
            var form = document.createElement("form");
            form.setAttribute("method", 'post');
            form.setAttribute("action", this.path);

            let csrfField = document.createElement('input');
            csrfField.setAttribute("type", "hidden");
            csrfField.setAttribute('name', '_token');
            csrfField.setAttribute('value', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            form.appendChild(csrfField);
        
            this.vehiclesList.filter(vehicle => {
                return vehicle.picked;
            }).forEach(vehicle => {
                var hiddenField = document.createElement('input');
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute('name', 'vehicles_id[]');
                hiddenField.setAttribute('value', vehicle.id);
                form.appendChild(hiddenField);
            });

            document.body.appendChild(form);
            form.submit();
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
