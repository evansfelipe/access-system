<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-car">
                Vehículo
            </tab-item>
        </ul>
        <!-- Content -->
        <div class="card card-default borderless-top-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <loading-cover v-if="!axios_finished" message="Cargando..."/>
                        <template v-else><vs-general-information v-show="tab === 0" :values="values"/></template>
                    </div>
                    <div class="col-1 text-right">
                        <!-- Edit button -->
                        <button @click="edit" class="btn btn-sm btn-outline-unique btn-circle" title="Editar perfil"><i class="fas fa-user-edit fa-lg"></i></button>
                        <!-- PDF button -->
                        <button class="btn btn-sm btn-outline-unique btn-circle" title="Exportar como PDF"><i class="fas fa-file-pdf fa-lg"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'vs-general-information':   require('./partials/GeneralInformation.vue'),
    },
    data: function() {
        return {
            axios_finished: false,
            tab: 0,
            edit_route: "",
            values: {}
        };
    },
    beforeMount() {
        axios.get(`/vehicles/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                this.values = response.data;
            })
            .catch(error => console.log(error));
    },
    methods: {
        edit: function() {

        }
    }
}
</script>
