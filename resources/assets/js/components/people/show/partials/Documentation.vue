<template>
    <div>
        <custom-table
            :columns="columns"
            :rows="documents"
            @rowclicked="openDocument"
        />
    </div>
</template>

<script>
export default {
    props: {
        documents: {
            type: Array,
            required: false,
            default: () => []
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
            window.open('google.com', '_blanck', 'titlebar=no');
        }
    }
}
</script>

