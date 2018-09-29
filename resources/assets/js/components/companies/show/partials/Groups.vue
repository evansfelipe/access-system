<template>
    <div>
        <custom-table
            :columns="columns"
            :rows="values"
            :no-rows-message="'No hay grupos asignados'"
            @rowclicked="({id}) => $router.push(`/groups/show/${id}`)"
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
                { name: 'name',         text: 'Nombre',         width:'50'},
                { name: 'gate',         text: 'Entrada',        width:'25'},
                { name: 'range',        text: 'Franja horaria', width:'25'}
            ]
        }
    }
}
</script>
