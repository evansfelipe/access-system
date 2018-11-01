<template>
    <remote-custom-table    list="vehicles" 
                            :columns="columns"
                            :filters="filters"
                            @rowclicked="({id}) => $router.push(`/vehicles/show/${id}`)"
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
                { name: 'plate',     text: 'Patente' },
                { name: 'brand',     text: 'Marca'   },
                { name: 'model',     text: 'Modelo'  },
                { name: 'year',      text: 'Año'     },
                { name: 'colour',    text: 'Color'   },
            ],
            filters: {
                id: this.values.length > 0 ? this.values : [-1],
            }
        }
    }
}
</script>

