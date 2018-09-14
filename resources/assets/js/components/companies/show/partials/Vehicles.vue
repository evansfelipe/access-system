<template>
    <div>
        <custom-table
            :columns="columns"
            :rows="values"
            :no-rows-message="'No hay vehículos asignados'"
            @rowclicked="({id}) => $router.push(`/vehicles/show/${id}`)"
        />
    </div>
</template>

<script>
export default {
    props: {
        values: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {
            columns: [
                {name: 'plate',     text: 'Patente'},
                {name: 'brand',     text: 'Marca'},
                {name: 'model',     text: 'Modelo'},
                {name: 'year',      text: 'Año'},
                {name: 'colour',    text: 'Color'},
            ]
        }
    }
}
</script>
