<style lang="scss" scoped>
    .btn-circle {
        margin-bottom: 0.5em;
        border-radius: 100%;
        height: 3em;
        width: 3em;
    }
</style>

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
            <!-- Card tab -->
            <tab-item :active="tab === 3" @click.native="tab = 3" icon="fas fa-id-card">
                Tarjeta
            </tab-item>
            <!-- Documentation tab -->
            <tab-item :active="tab === 4" @click.native="tab = 4" icon="fas fa-file-alt">
                Documentación
            </tab-item>
            <!-- Observations tab -->
            <tab-item :active="tab === 5" @click.native="tab = 5" icon="fas fa-file-alt">
                Observaciones
            </tab-item>
        </ul>
        <!-- Content -->
        <div class="card card-default borderless-top-card ">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <loading-cover v-if="!axios_finished" message="Cargando..."/>
                        <!-- Content for the tab number 0 -->
                        <ps-personal-information v-show="tab === 0" :person="personal_information"/>
                        <!-- Content for the tab number 1 -->
                        <ps-working-information v-show="tab === 1" :personCompany="working_information"/>
                        <!-- Content for the tab number 2 -->
                        <ps-vehicles v-show="tab === 2" :vehicles="vehicles"/>
                        <!-- Content for the tab number 3 -->
                        <!-- <ps-cards   v-show="tab === 3" :activeCard="active_card" :inactiveCards="inactive_cards"
                                    :person="personal_information.full_name || ''" :company="working_information.company_name || ''"/> -->
                        <!-- Content for the tab number 4 -->
                        <ps-documentation v-show="tab === 4"/>
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
            'ps-cards': require('./partials/Cards.vue'),
            'ps-documentation': require('./partials/Documentation.vue'),
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
                inactive_cards: []
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
