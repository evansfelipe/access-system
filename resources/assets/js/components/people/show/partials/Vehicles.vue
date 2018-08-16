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
            <div v-else>
                <custom-table
                    :columns="columns"
                    :rows="vehicles"
                    @rowclicked="showVehicle"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        vehicles: {
            type: Array,
            required: false,
            default: () => []
        }
    },
    data: function() {
        return {
            columns: [ 
                {name: 'plate', text: 'Patente'},
                {name: 'brand', text: 'Marca'},
                {name: 'model', text: 'Modelo'},
                {name: 'year', text: 'Año'},
                {name: 'colour', text: 'Color'},
            ],
        }
    },
    methods: {
        showVehicle(id) {

        }
    }
}
</script>
