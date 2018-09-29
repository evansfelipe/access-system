<template>
    <custom-table
        :columns="columns"
        :rows="people"
        :rowsquantity="10"
        :no-rows-message="'No hay personas asignados'"
        @rowclicked="({id}) => $router.push(`/people/show/${id}`)"
    />
</template>

<script>
export default {
    props: {
        people: {
            type:     Array,
            required: false,
            default:  () => []
        }
    },
    data: function() {
        return {
            columns: [ 
                {name: 'last_name', text: 'Apellido'},
                {name: 'name',      text: 'NOmbre'},
                {name: 'cuil',      text: 'CUIL / CUIT'},
            ],
        }
    }
}
</script>