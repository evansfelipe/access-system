<template>
    <remote-custom-table    list="people" 
                            :columns="columns"
                            :filters="filters"
                            @rowclicked="({id}) => $router.push(`/people/show/${id}`)"
                            :paginate-on-mounted="true"
    />
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
                { name: 'last_name', text: 'Apellido'    },
                { name: 'name',      text: 'Nombre'      },
                { name: 'cuil',      text: 'CUIL / CUIT' }
            ],
            filters: {
                id: this.values.length > 0 ? this.values : [-1],
            },
        }
    }
}
</script>