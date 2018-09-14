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
        <show-wrapper :loading="!axios_finished" @edit="edit" @pdf="pdf">
            <personal-information v-show="tab === 0" :person="personal_information"/>
            <working-information v-show="tab === 1" :personCompany="working_information"/>
            <vehicles v-show="tab === 2" :vehicles="vehicles"/>
            <documentation v-show="tab === 3" :documents="documents"/>
            <observations v-show="tab === 4" :personObservations="observations"/>
        </show-wrapper>
    </div>
</template>

<script>
    export default {
        components: {
            'personal-information': require('./partials/PersonalInformation.vue'),
            'working-information': require('./partials/WorkingInformation.vue'),
            'vehicles': require('./partials/Vehicles.vue'),
            'documentation': require('./partials/Documentation.vue'),
            'observations': require('./partials/Observations.vue'),
        },
        data: function() {
            return {
                axios_finished: false,
                tab: 0,
                personal_information: {},
                working_information: {},
                vehicles: [],
                observations: [],
                documents: []
            };
        },
        beforeMount() {
            axios.get(`/people/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                let person_info = response.data;
                this.personal_information = person_info.personal_information;
                this.working_information = person_info.working_information;
                this.vehicles = person_info.vehicles;
                this.observations = person_info.observations;
                this.documents = person_info.documents;
            })
            .catch(error => {
                console.log(error);
            })
        },
        methods: {
            edit: function() {
                this.$store.dispatch('fetchModel', { which: 'person', id: this.$route.params.id });
                this.$router.push(`/people/create`);
            },
            pdf: function() {
                window.open(`/people/${this.$route.params.id}/pdf`, '_blanck', 'titlebar=no');
            }
        }
    }
</script>
