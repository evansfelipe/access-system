<template>
    <div>
        <custom-table
            :columns="columns"
            :rows="values"
            @rowclicked="({id}) => $router.push(`/people/show/${id}`)"
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
                {name: 'last_name', text: 'Apellido'},
                {name: 'name', text: 'Nombre'},
                {name: 'cuil', text: 'CUIL / CUIT'}
            ]
        }
    }
}
</script>
