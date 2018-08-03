<template>
    <div>
        <ul class="nav nav-tabs">
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.general_information" icon="fas fa-building">
                Información general
            </tab-item>
        </ul>
        <div class="card card-default borderless-top-card ">
            <div class="card-body">
                <general-information v-show="tab === 0" :values="values.general_information" :errors="errors"></general-information>
                <!-- Buttons -->
                <hr>
                <div class="row">
                    <div class="col">
                        <confirmable-button btnclass="btn btn-outline-danger btn-sm" @confirmed="cancel">Cancelar</confirmable-button>
                        <button class="btn btn-outline-success btn-sm float-right" @click="save">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'general-information': require('./partials/GeneralInformation'),
    },
    data: function() {
        return {
            tab: 0,
            step_validated: {
                general_information: null,
            },
            errors: {}
        };
    },
    computed: {
        values: function() {
            return this.$store.state.models.company.values;
        }
    },
    mounted() {
        let callback = (values, properties_path) => this.$store.commit('updateModel', { which: 'company', properties_path: properties_path, value: values });
        this.$on('general-information-values', values => callback(values, 'values.general_information'));
    },
    methods: {
        cancel: function() {
            this.$store.commit('resetModel', 'company');
            this.$router.go(-1);
        },
        save: function() {
            this.$store.commit('loading', { state: true, message: "Guardando..." });
            this.step_validated = {
                general_information: true,
            }
            axios.post('/companies', { ...this.values.general_information })
            .then(response => {
                this.$store.dispatch('addNotification', {type: 'success', message: `Empresa creada exitosamente.`});
            })
            .catch(error => {
                this.step_validated.general_information = false;
                if(error.response.status === 422) {
                    this.errors = error.response.data.errors;
                    this.$store.dispatch('addNotification', {type: 'danger', message: 'Corrija los errores antes de continuar.'});
                }
            })
            .finally(() => this.$store.commit('loading', { state: false, message: "" }));
        }
    }
}
</script>

