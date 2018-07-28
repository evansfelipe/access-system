<style lang="scss" scoped>
 
    div.table-container {
        height: 65vh;
    }
</style>

<template>
    <div class="row">
        <div class="col">
            <div v-if="vehicles.length==0" class="table-container d-flex align-items-center justify-content-center">
                <h4>No hay vehículos asignados</h4>
            </div>
            <div v-if="vehicles.length>0" class="table-container">
                <table class="table tbl-show">
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
                        <tr v-for="(vehicle, key) in vehicles" :key="key">
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
    </div>
</template>

<script>
export default {
    props: {
        vehicles: {
            requires: false,
            default: () => []
        }
    }
}
</script>
