<template>
    <div class="row">
        <div class="col">
            <div v-if="vehicles.length==0" style="text-align:center">
                <h4>No hay vehículos asignados</h4>
            </div>
            <div v-if="vehicles.length>0" style="text-align:center">
                <h4>No hay vehículos asignados</h4>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        vehicles: {
            requires: false,
            default: []
        }
    }
}
</script>
