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
        <div class="card card-default borderless-top">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <loading-cover v-if="!axios_finished"/>
                        <template v-else>
                            <cs-general-information v-show="tab === 0" :values="values.general_information"/>
                            <cs-people v-show="tab === 1" :values="values.assigned_people"/>
                            <cs-vehicles v-show="tab === 2" :values="values.assigned_vehicles"/>
                        </template>
                    </div>
                    <div class="col-1 text-right">
                        <!-- Edit button -->
                        <button @click="edit" class="btn btn-sm btn-outline-unique btn-circle" title="Editar perfil"><i class="fas fa-pen-square fa-lg"></i></button>
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
        'cs-general-information': require('./partials/GeneralInformation.vue'),
        'cs-people': require('./partials/People.vue'),
        'cs-vehicles': require('./partials/Vehicles.vue'),
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
        }
    }
}
</script>
