<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-car">
                Vehículo
            </tab-item>
            <!-- Containers tab -->
            <tab-item v-if="allows_containers" :active="tab === 1" @click.native="tab = 1" icon="fas fa-boxes">
                Contenedores
            </tab-item>
        </ul>
        <!-- Content -->
        <show-wrapper :loading="!axios_finished" @edit="edit" @pdf="pdf">
            <general-information v-show="tab === 0" :values="general_information"/>
            <containers v-show="tab === 1" :containers="containers"/>
        </show-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information':   require('./partials/GeneralInformation.vue'),
        'containers':   require('./partials/Containers.vue'),
    },
    data: function() {
        return {
            axios_finished: false,
            tab: 0,
            edit_route: "",
            general_information: {},
            containers: []
        };
    },
    computed: {
        allows_containers: function() {
            if(this.$store.getters.vehicle_types.timestamp !== null && this.axios_finished){
                let vehicle_type = this.$store.getters.vehicle_types.list.getById(this.general_information.type.id);
                return vehicle_type.allows_container;
            }
            return false;
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','vehicle_types');
        axios.get(`/vehicles/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                this.containers = response.data.assigned_containers;
                this.general_information = response.data;
                delete this.general_information.assigned_containers;
            })
            .catch(error => console.log(error));
    },
    methods: {
        edit: function() {
            this.$store.dispatch('fetchModel', { which: 'vehicle', id: this.$route.params.id });
            this.$router.push(`/vehicles/create`);
        },
        pdf: function() {
            
        }
    }
}
</script>
