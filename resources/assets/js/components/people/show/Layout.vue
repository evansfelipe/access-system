<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- Personal information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-user">
                Información personal
            </tab-item>
            <!-- Working information tab -->
            <tab-item :active="tab === 1" @click.native="tab = 1" icon="fas fa-briefcase">
                Información laboral
            </tab-item>
            <!-- Vehicles tab -->
            <tab-item :active="tab === 2" @click.native="tab = 2" icon="fas fa-car">
                Vehículos
            </tab-item>
            <!-- Documentation tab -->
            <tab-item :active="tab === 3" @click.native="tab = 3" icon="fas fa-file-alt">
                Documentación
            </tab-item>
            <!-- Observations tab -->
            <tab-item :active="tab === 4" @click.native="tab = 4" icon="fas fa-eye">
                Observaciones
            </tab-item>
        </ul>
        <!-- Content -->
        <div class="card card-default borderless-top">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <loading-cover v-if="!axios_finished" message="Cargando..."/>
                        <ps-personal-information v-show="tab === 0" :person="personal_information"/>
                        <ps-working-information v-show="tab === 1" :personCompany="working_information"/>
                        <ps-vehicles v-show="tab === 2" :vehicles="vehicles"/>
                        <ps-documentation v-show="tab === 3"/>
                        <ps-observations v-show="tab === 4" :personObservations="observations"/>
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
            'ps-personal-information': require('./partials/PersonalInformation.vue'),
            'ps-working-information': require('./partials/WorkingInformation.vue'),
            'ps-vehicles': require('./partials/Vehicles.vue'),
            'ps-documentation': require('./partials/Documentation.vue'),
            'ps-observations': require('./partials/Observations.vue'),
        },
        data: function() {
            return {
                axios_finished: false,
                tab: 0,
                edit_route: "",
                personal_information: {},
                working_information: {},
                vehicles: [],
                active_card: {},
                inactive_cards: [],
                observations: []
            };
        },
        beforeMount() {
            axios.get(`/people/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                let person_info = response.data;
                this.edit_route = person_info.edit_url;
                this.personal_information = person_info.personal_information;
                this.working_information = person_info.working_information;
                this.vehicles = person_info.vehicles;
                this.active_card = person_info.active_card;
                this.inactive_cards = person_info.inactive_cards;
                this.observations = person_info.observations;
            })
            .catch(error => {
                console.log(error);
            })
        },
        methods: {
            edit: function() {
                this.$store.dispatch('fetchModel', { which: 'person', id: this.$route.params.id });
                this.$router.push(`/people/create`);
            }
        }
    }
</script>
