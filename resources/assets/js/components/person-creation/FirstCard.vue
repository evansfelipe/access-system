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
                            placeholder="Número de tarjeta" v-model="values.number">
                </form-item>
                <div class="active-card">
                    <div class="title">
                        <h4>Tarjeta de acceso</h4>
                    </div>
                    <hr>
                    <div class="info">
                        <h3 id="display">xxxx-xxxx</h3>
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
                        <select name="risk" class="form-control" v-model="values.risk">
                            <option value="" hidden>Seleccione riesgo</option>
                            <option value="1">Nivel 1</option>
                            <option value="2">Nivel 2</option>
                            <option value="3">Nivel 3</option>
                        </select>
                    </div>
                </form-item>
            </div>
            <div class="form-row">
                <form-item label="Validez" :errors="errors.from.concat(errors.until)">
                    <div class="col-6">
                        <small>Desde:</small>
                        <input type="date" name="from" class="form-control" v-model="values.from">
                    </div>
                    <div class="col-6">
                        <small>Hasta:</small>
                        <input type="date" name="until" class="form-control" v-model="values.until">
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
            required: false,
            default: 'x'
        },
        companyname: {
            required: false,
            default: 'x'
        }
    },
    data: () => {
        return {
            errors: {},
            values: {
                number: '',
                risk: '',
                from: '',
                until: '',
            }
        };
    },
    beforeMount() {
        this.setErrors({})
    },
    methods: {
        setErrors: function(new_errors) {
            this.errors = {
                number: new_errors.number ? new_errors.number : [],
                risk: new_errors.risk ? new_errors.risk : [],
                from: new_errors.from ? new_errors.from : [],
                until: new_errors.until ? new_errors.until : [],
            }
        },
        save: function() {
            axios.post('person-creation/first-card', this.values)
            .then(response => {
                console.log('First card:', response);
                this.$parent.$emit('first-card-saved', true);

            })
            .catch(error => {
                this.errors = error.response.data.errors;
                this.$parent.$emit('first-card-saved', false);

            })
        }
    },
}
</script>