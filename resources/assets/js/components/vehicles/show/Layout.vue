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
        <show-wrapper :loading="!axios_finished" @edit="edit" @pdf="pdf">
            <general-information v-show="tab === 0" :values="general_information"/>
        </show-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information':   require('./partials/GeneralInformation.vue'),
    },
    data: function() {
        return {
            axios_finished: false,
            tab: 0,
            edit_route: "",
            general_information: {},
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList','vehicle_types');
        axios.get(`/vehicles/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                this.general_information = response.data;
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
