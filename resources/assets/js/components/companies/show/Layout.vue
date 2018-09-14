<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-building">
                Información general
            </tab-item>
            <!-- Assigned people tab -->
            <tab-item :active="tab === 1" @click.native="tab = 1" icon="fas fa-users">
                Personas
            </tab-item>
            <!-- Assigned vehicles tab -->
            <tab-item :active="tab === 2" @click.native="tab = 2" icon="fas fa-car">
                Vehículos
            </tab-item>
        </ul>
        <!-- Content -->
        <show-wrapper :loading="!axios_finished" @edit="edit" @pdf="pdf">
            <general-information v-show="tab === 0" :values="values.general_information"/>
            <people v-show="tab === 1" :values="values.assigned_people"/>
            <vehicles v-show="tab === 2" :values="values.assigned_vehicles"/>
        </show-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information': require('./partials/GeneralInformation.vue'),
        'people': require('./partials/People.vue'),
        'vehicles': require('./partials/Vehicles.vue'),
    },
    data: function() {
        return {
            axios_finished: false,
            tab: 0,
            edit_route: "",
            values: {
                general_information: {},
                assigned_people: [],
                assigned_vehicles: []
            }
        };
    },
    beforeMount() {
        axios.get(`/companies/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                this.values.general_information = response.data.general_information;
                this.values.assigned_people = response.data.assigned_people;
                this.values.assigned_vehicles = response.data.assigned_vehicles;
            })
            .catch(error => console.log(error));
    },
    methods: {
        edit: function() {
            this.$store.dispatch('fetchModel', { which: 'company', id: this.$route.params.id });
            this.$router.push(`/companies/create`);
        },
        pdf: function() {
            
        }
    }
}
</script>
