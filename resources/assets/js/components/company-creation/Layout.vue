<style>
    .card {
        border-top: 0;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }
</style>

<template>
    <div>
        <ul class="nav nav-tabs">
            <tab-item :active="tab === 0" @click.native="tab = 0" :has-errors="step_validated.general_information" icon="fas fa-building">
                Información general
            </tab-item>
        </ul>
        <div class="card card-default">
            <div class="card-body">
                <!-- Loading cover will be only be rendered when component is saving the data to the server -->
                <loading-cover v-if="saving.status" :message="saving.message"/>
                <general-information v-show="tab === 0" :values="values.general_information" :errors="errors"></general-information>
                <!-- Buttons -->
                <hr>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-outline-danger btn-sm" href="/home">Cancelar</a>
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
        'general-information': require('./GeneralInformation'),
    },
    data: function() {
        return {
            tab: 0,
            saving: {
                status: false,
                message: ''
            },
            step_validated: {
                general_information: null,
            },
            values: {
                general_information: {
                    name: '',
                    area: '',
                    cuit: '',
                    expiration: '',
                    phone: '',
                    fax: '',
                    email: '',
                    web: '',
                    street: '',
                    apartment: '',
                    cp: '',
                    country: '',
                    province: '',
                    city: ''
                }
            },
            errors: {}
        };
    },
    mounted() {
        this.$on('general-information-values', values => this.values.general_information = values);
    },
    methods: {
        save: function() {
            window.scrollTo(0,0);
            this.saving = { status: true, message: "Guardando..." };
            axios.post('/companies', { ...this.values.general_information })
            .then(response => {
                this.saving.message = "Redirigiendo...";
                window.location.href = response.data;
            })
            .catch(error => {
                if(error.response.status === 422) {
                    this.errors = error.response.data.errors;
                    this.saving.status = false;
                }
                else {
                    console.log(error.response);
                }
            })
        }
    }
}
</script>

