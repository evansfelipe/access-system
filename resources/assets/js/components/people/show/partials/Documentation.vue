<template>
    <div>
        <div class="row">
            <div class="col">
                    <custom-table
                        :columns="columns"
                        :rows="documents"
                        :rowsquantity="10"
                        no-rows-message="No hay documentación subida"
                        @rowclicked="openDocument"
                    />
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 col-lg-6">
                <hr-label>Documentación requerida para el ingreso</hr-label>
                <h6 v-for="(doc, key) in requiredDocuments" :key="key">
                    <i class="far fa-check-circle"></i> {{ doc }}
                </h6>
            </div>
            <div class="col-md-12 col-lg-6">
                <hr-label>Documentación no requerida para el ingreso</hr-label>
                <h6 v-for="(doc, key) in notRequiredDocuments" :key="key">
                    <i class="far fa-circle"></i> {{ doc }}
                </h6>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        documents: {
            type:     Array,
            required: false,
            default:  () => []
        },
        requiredDocuments: {
            required: false,
        },
        notRequiredDocuments: {
            required: false,
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

