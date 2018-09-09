<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.general_information" icon="fas fa-car">
                Información general
            </tab-item>
            <!-- Assign people tab -->
            <tab-item :active="tab === 1" @click.native="tab = 1" :has-errors="step_validated.assign_people" icon="fas fa-users">
                Asignar personas
            </tab-item>
        </ul>
        <!-- Card -->
        <creation-wrapper   :updating="this.$store.getters.vehicle.updating" :values="values" :route="route" 
                            @saveSuccess="saveSuccess" @saveFailed="saveFailed" @cancel="cancel">
            <general-information v-show="tab === 0" :errors="general_information_errors" :values="values.general_information"/>
            <assign-people v-show="tab === 1" :errors="assign_people_errors" :values="values.assign_people" :companyid="company_id" :companyname="company_name"/>
        </creation-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information': require('./partials/GeneralInformation.vue'),
        'assign-people': require('./partials/AssignPeople.vue'),
    },
    data: function() {
        return {
            tab: 0,
            first_save: false,
            errors: [],
            step_validated: {
                general_information: null,
                assign_people:       null,
            }
        }
    },
    computed: {
        id: function() {
            return this.$store.getters.vehicle.id;
        },   
        values: function() {
            return this.$store.getters.vehicle.values;
        },
        route: function() {
            return {
                method: this.id ? 'put' : 'post',
                url:    this.id ? `/vehicles/${this.id}` : '/vehicles'
            }
        },
        company_id: function() {
            let ret = null;
            if(this.values.general_information.company_id && !this.$store.getters.companies.updating) {
                ret = this.values.general_information.company_id;
            }
            return parseInt(ret);
        },
        company_name: function() {
            let ret = '';
            if(this.values.general_information.company_id && !this.$store.getters.companies.updating) {
                let val = this.$store.getters.companies.list.filter(company => company.id == this.values.general_information.company_id);
                return val.length > 0 ? val[0].name : '-';
            }
            return ret;
        },
        general_information_errors: function() {
            return this.errors['general_information'] ? this.errors['general_information'] : []; 
        },
        assign_people_errors: function() {
            return this.errors['assign_people'] ? this.errors['assign_people'] : []; 
        }
    },
    methods: {
        saveSuccess: function(id) {
            this.$router.push(`/vehicles/show/${id}`);
            this.$store.dispatch('addNotification', {type: 'success', message: `Vehículo ${this.id ? 'editado' : 'creado'} exitosamente.`});
            this.$store.commit('resetModel', 'person');
            this.first_save = true;
        },
        saveFailed: function({ errors, step_validated }) {
            this.errors = errors;
            this.step_validated = step_validated;
            this.first_save = true;
        },
        cancel: function() {
            this.$router.go(-1);
            this.$store.commit('resetModel', 'vehicle');
        },
    }
}
</script>
