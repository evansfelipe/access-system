<template>
    <custom-table
        :columns="columns"
        :rows="vehicles"
        :rowsquantity="10"
        @rowclicked="({id}) => $router.push(`/vehicles/show/${id}`)"
    />
</template>

<script>
export default {
    props: {
        vehicles: {
            type:     Array,
            required: false,
            default:  () => []
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
    }
}
</script>
