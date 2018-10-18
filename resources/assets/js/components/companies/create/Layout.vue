<template>
    <div>
        <ul class="nav nav-tabs">
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.general_information" icon="fas fa-building">
                Información general
            </tab-item>
            <tab-item :active="tab === 1" @click.native="tab = 1" :has-errors="step_validated.assign_groups" icon="fas fa-universal-access">
                Asignar grupos
            </tab-item>
        </ul>

        <creation-wrapper   :updating="this.$store.getters.company.updating" :values="values" :route="route" 
                            @saveSuccess="saveSuccess" @saveFailed="saveFailed" @cancel="cancel">
                <general-information v-show="tab === 0" :values="values.general_information" :errors="general_information_errors" />
                <assign-groups v-show="tab === 1" :values="values.assign_groups" :errors="assign_groups_errors" :companyname="values.general_information.name" />
        </creation-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information': require('./partials/GeneralInformation'),
        'assign-groups': require('./partials/AssignGroups/Layout'),
    },
    data: function() {
        return {
            tab: 0,
            first_save: false,
            errors: [],
            step_validated: {
                general_information: null,
                assign_groups: null,
            }
        };
    },
    computed: {
        id: function() {
            return this.$store.getters.company.id;
        },
        values: function() {
            return this.$store.getters.company.values;
        },
        route: function() {
            return {
                method: this.id ? 'put' : 'post',
                url:    this.id ? `/companies/${this.id}` : '/companies'
            }
        },
        general_information_errors: function() {
            return this.errors['general_information'] ? this.errors['general_information'] : []; 
        },
        assign_groups_errors: function() {
            return this.errors['assign_groups'] ? this.errors['assign_groups'] : []; 
        }
    },
    methods: {
        saveSuccess: function(id) {
            this.$router.push(`/companies/show/${id}`);
            this.$store.dispatch('addNotification', {type: 'success', message: `Empresa ${this.id ? 'editada' : 'creada'} exitosamente.`});
            this.$store.commit('resetModel', 'company');
            this.first_save = true;
        },
        saveFailed: function({errors, step_validated}) {
            this.errors = errors;
            this.step_validated = step_validated;
            this.first_save = true;
        },
        cancel: function() {
            this.$router.go(-1);
            this.$store.commit('resetModel', 'company');
        }
    }
}
</script>

