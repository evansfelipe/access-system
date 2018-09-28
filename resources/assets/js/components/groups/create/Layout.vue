<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.general_information" icon="fas fa-universal-access">
                Información general
            </tab-item>
        </ul>
        <!-- Card -->
        <creation-wrapper   :updating="this.$store.getters.vehicle.updating" :values="values" :route="route" 
                            @saveSuccess="saveSuccess" @saveFailed="saveFailed" @cancel="cancel">
            <general-information v-show="tab === 0" :errors="general_information_errors" :values="values.general_information"/>
        </creation-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information': require('./partials/GeneralInformation.vue'),
    },
    data: function() {
        return {
            tab: 0,
            first_save: false,
            errors: [],
            step_validated: {
                general_information: null,
            }
        }
    },
    computed: {
        id: function() {
            return this.$store.getters.group.id;
        },   
        values: function() {
            return this.$store.getters.group.values;
        },
        route: function() {
            return {
                method: this.id ? 'put' : 'post',
                url:    this.id ? `/groups/${this.id}` : '/groups'
            }
        },
        general_information_errors: function() {
            return this.errors['general_information'] ? this.errors['general_information'] : []; 
        }
    },
    methods: {
        saveSuccess: function(id) {
            this.$router.push(`/groups/show/${id}`);
            this.$store.dispatch('addNotification', {type: 'success', message: `Contenedor ${this.id ? 'editado' : 'creado'} exitosamente.`});
            this.$store.commit('resetModel', 'container');
            this.first_save = true;
        },
        saveFailed: function({ errors, step_validated }) {
            this.errors = errors;
            this.step_validated = step_validated;
            this.first_save = true;
        },
        cancel: function() {
            this.$router.go(-1);
            this.$store.commit('resetModel', 'container');
        },
    }
}
</script>
