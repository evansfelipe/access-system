<style scoped>
    div.active-card {
        display: inline-block;
        width: 100%;
        border-radius: 5px;
        color: white;
        background: linear-gradient(to right, #0d47a1 , #4285F4);
    }

    div.active-card > hr {
        height: 12px;
        border: 0;
        box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
        margin: 0;
    }

    div.active-card > div.title {
        padding: 5%;
        padding-bottom: 5px;
    }

    div.active-card > div.title > h4 {
        display: inline-block;
    }

    div.active-card > div.info {
        padding: 5%;
        padding-top: 0;
    }
</style>

<template>
    <div>
        <div class="form-row d-flex align-items-center">
            <div class="offset-1 col-4">
                <form-item :errors="errors.number">
                    <input  type="text" name="number" class="form-control"
                            placeholder="Número de tarjeta" :value="values.number" @input="(e) => update(e.target)">
                </form-item>
                <div class="active-card">
                    <div class="title">
                        <h4>Tarjeta de acceso</h4>
                    </div>
                    <hr>
                    <div class="info">
                        <h3 id="display">{{ values.number !== '' ? values.number : 'xxx-xxx-xxx-xxx'}}</h3>
                        <small><b>{{ fullname }}</b></small>
                        <br>
                        <small><b>{{ companyname }}</b></small>
                        <br>
                    </div>
                </div>
            </div>
            <div class="offset-1 col-5">
            <div class="form-row">
                <form-item label="Riesgo" :errors="errors.risk">
                    <div class="col">
                        <select2    name="risk" :value="values.risk" @input="(value) => update({name: 'risk', value: value})"
                                    placeholder="Seleccione nivel de riesgo" :options="risk_levels"/>
                    </div>
                </form-item>
            </div>
            <div class="form-row">
                <form-item label="Validez" :errors="validity_errors">
                    <div class="col-6">
                        <small>Desde:</small>
                        <input type="date" name="from" class="form-control" :value="values.from" @input="(e) => update(e.target)">
                    </div>
                    <div class="col-6">
                        <small>Hasta:</small>
                        <input type="date" name="until" class="form-control" :value="values.until" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        fullname: {
            type: String,
            required: false,
            default: 'x, x'
        },
        companyname: {
            type: String,
            required: false,
            default: 'x'
        },
        values: {
            required: true,
            type: Object
        },
        errors: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            risk_levels: [
                {id: 1, text: 'Nivel 1'},
                {id: 2, text: 'Nivel 2'},
                {id: 3, text: 'Nivel 3'},
            ],
        }
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.first_card.${name}`, value: value });
        },        
    },
    computed: {
        validity_errors: function() {
            if(this.errors.from && this.errors.until) {
                return this.errors.from.concat(this.errors.until);
            }
            else if (this.errors.from) {
                return this.errors.from;
            }
            else if (this.errors.until) {
                return this.errors.until;
            }
            else {
                return [];
            }
        }
    }
}
</script>