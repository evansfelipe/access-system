<template>
    <custom-table
        :columns="columns"
        :rows="documents"
        :rowsquantity="10"
        @rowclicked="openDocument"
    />
</template>

<script>
export default {
    props: {
        documents: {
            type:     Array,
            required: false,
            default:  () => []
        }
    },
    data() {
        return {
            columns: [
                {name: 'type',       text: 'Tipo de documento'},
                {name: 'created_at', text: 'Creación'},
                {name: 'expiration', text: 'Expiración'},
            ]
        };
    },
    methods: {
        openDocument: function(doc) {
            window.open(`people/document/${doc.id}`, '_blanck', 'titlebar=no');
        }
    }
}
</script>

