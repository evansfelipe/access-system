<template>
    <div>
        <show-wrapper :loading="!axios_finished" :enabled="enabled" @edit="edit" @pdf="pdf" @toggle-enabled="toggleEnabled">
            <!-- Tabs -->
            <ul slot="tabs" class="nav nav-tabs">
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
            <personal-information v-show="tab === 0" :person="personal_information"/>
            <working-information v-show="tab === 1" :jobs="jobs"/>
            <vehicles v-show="tab === 2" :vehicles="vehicles"/>
            <documentation v-show="tab === 3" :documents="documents" :required-documents="required_documents" :not-required-documents="not_required_documents"/>
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
                enabled: false,
                personal_information: {},
                jobs: [],
                vehicles: [],
                observations: [],
                documents: [],
                required_documents: [],
                not_required_documents: [],
            };
        },
        beforeMount() {
            axios.get(`/people/${this.$route.params.id}`)
            .then(response => {
                this.enabled = response.data.enabled;
                this.personal_information = response.data.personal_information;
                this.jobs = response.data.jobs;
                this.vehicles = response.data.vehicles;
                this.observations = response.data.observations;
                this.documents = response.data.documents;
                this.required_documents = response.data.required_documents;
                this.not_required_documents = response.data.not_required_documents;
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                this.axios_finished = true;
            });
        },
        methods: {
            edit: function() {
                this.$store.dispatch('fetchModel', { which: 'person', id: this.$route.params.id });
                this.$router.push(`/people/create`);
            },
            pdf: function() {
                window.open(`/people/${this.$route.params.id}/pdf`, '_blanck', 'titlebar=no');
            },
            toggleEnabled: function() {
                axios.post(`/people/${this.$route.params.id}/toggle-enabled`)
                .then(response => this.enabled = response.data.enabled)
                .catch(error => {})
            }
        }
    }
</script>
