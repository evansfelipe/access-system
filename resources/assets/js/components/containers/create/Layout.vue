<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.general_information" icon="fas fa-box">
                Información general
            </tab-item>
            <!-- Assign people tab -->
            <tab-item :active="tab === 1" @click.native="tab = 1" :has-errors="step_validated.assign_trucks" icon="fas fa-truck-pickup">
                Asignar camiones
            </tab-item>
        </ul>
        <!-- Card -->
        <creation-wrapper   :updating="this.$store.getters.vehicle.updating" :values="values" :route="route" 
                            @saveSuccess="saveSuccess" @saveFailed="saveFailed" @cancel="cancel">
            <general-information v-show="tab === 0" :errors="general_information_errors" :values="values.general_information"/>
            <assign-trucks v-show="tab === 1" />
        </creation-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information': require('./partials/GeneralInformation.vue'),
        'assign-trucks': require('./partials/AssignTrucks.vue'),
    },
    data: function() {
        return {
            tab: 0,
            first_save: false,
            errors: [],
            step_validated: {
                general_information: null,
                assign_trucks:       null,
            }
        }
    },
    computed: {
        id: function() {
            return this.$store.getters.container.id;
        },   
        values: function() {
            return this.$store.getters.container.values;
        },
        route: function() {
            return {
                method: this.id ? 'put' : 'post',
                url:    this.id ? `/containers/${this.id}` : '/containers'
            }
        },
        company_id: function() {
            
        },
        company_name: function() {
            
        },
        general_information_errors: function() {
            return this.errors['general_information'] ? this.errors['general_information'] : []; 
        },
        assign_people_errors: function() {
            return this.errors['assign_trucks'] ? this.errors['assign_trucks'] : []; 
        }
    },
    methods: {
        saveSuccess: function(id) {
            this.$router.push(`/containers/show/${id}`);
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
