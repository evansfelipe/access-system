<template>
    <custom-table
        :columns="columns"
        :rows="containers"
        :rowsquantity="10"
        :no-rows-message="'No hay contenedores asignados'"
        @rowclicked="({id}) => $router.push(`/containers/show/${id}`)"
    />
</template>

<script>
export default {
    props: {
        containers: {
            type:     Array,
            required: false,
            default:  () => []
        }
    },
    data: function() {
        return {
            columns: [ 
                {name: 'series_number', text: 'Número de serie', width: '25'},
                {name: 'format',        text: 'Formato',         width: '25'},
                {name: 'brand',         text: 'Marca',           width: '25'},
                {name: 'model',         text: 'Modelo',          width: '25'}
            ],
        }
    }
}
</script>