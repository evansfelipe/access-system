<style scoped>
    .card-body { min-height: 30vh }
</style>

<template>
    <div class="card card-default borderless-top-card">
        <div class="card-body">
            <loading-cover v-if="updating" message="Cargando..."/>
            <template v-else>
                <slot/>
                <hr>
                <div class="row">
                    <div class="col">
                        <confirmable-button btnclass="btn btn-outline-danger btn-sm" @confirmed="cancel">Cancelar</confirmable-button>
                        <button class="btn btn-outline-success btn-sm float-right" @click="save">Guardar</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        updating: {
            type: Boolean,
            required: false,
            default: false
        },
        values: {
            type: Object,
            required: true,
        },
        route: {
            type: Object,
            required: true
        }
    },
    watch: {
        values: {
            handler: function() {},
            deep: true
        }
    },
    methods: {
        cancel: function() {
            this.$emit('cancel');
        },
        save: function() {
            this.$store.commit('loading', { state: true, message: "Guardando..." });

            let onError = (response) => {
                let errors = {};
                let results = {};
                let r_errors = response.response.data.errors;
                Object.keys(this.values).forEach(key => {
                    errors[key] = {};
                    Object.keys(r_errors).forEach(error => {
                        let attributes = Object.keys(this.values[key]);
                        let found = false;
                        attributes.forEach(attr => {
                            if(error.startsWith(attr)) {
                                found = true;
                            }
                        })
                        if(found) {
                            errors[key][error] = r_errors[error];
                        }
                    });
                    results[key] = Object.keys(errors[key]).length > 0 ? false : true;
                });
                this.$store.dispatch('addNotification', {type: 'danger', message: 'Corrija los errores antes de continuar.'});
                this.$emit('saveFailed', {errors: errors, step_validated: results});
                console.log(r_errors);
                console.log(errors);
            }
            
            let data = new FormData();

            Object.keys(this.values).forEach(step => {
                Object.keys(this.values[step]).forEach(key => {
                    let val = this.values[step][key] ? this.values[step][key] : '';
                    data.append(key, Array.isArray(val) ? JSON.stringify(val) : val);
                });
            });

            if(this.route.method === 'put') {
                data.append('_method', 'PUT');
            }

            axios.post(this.route.url, data)
            .then(response => this.$emit('saveSuccess', response.data.id))
            .catch(response => onError(response))
            .finally(() => this.$store.commit('loading', { state: false, message: "" }));
        }
    }
}
</script>
