<template>
    <div>
        <!-- Company -->
        <div class="form-row">
            <form-item label="Empresa" :errors="errors.company_id">
                <div class="col-6">
                    <select name="company_id" class="form-control" v-model="values.company_id"> 
                        <option value="" hidden>Seleccione una empresa</option>
                        <option v-for="(company, key) in companies" :key="key"
                                :value="company.id">
                                {{ company.name }}
                        </option>                        
                    </select>
                </div>
                <div class="col-1 text-center">
                    <strong>o</strong>
                </div>
                <div class="col-5">
                    <button type="button" class="btn btn-block btn-outline-unique">Cree una nueva empresa</button>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input">
                        <label class="form-check-label">Monotributista</label>
                    </div>
                </div>
            </form-item>
        </div>
        <!-- Activity -->
        <div class="form-row">
            <form-item label="Actividad / Categoría" :errors="errors.activity_id">
                <div class="col-6">
                    <select name="activity_id" class="form-control" v-model="values.activity_id">
                        <option value="" hidden>Seleccione actividad</option>
                        <option v-for="(activity, key) in activities" :key="key"
                                :value="activity.id">
                                {{ activity.name }}
                        </option>
                    </select>                    
                </div>
                <div class="col-1 text-center">
                    <strong>o</strong>
                </div>
                <div class="col-5">
                    <button type="button" class="btn btn-block btn-outline-unique">Cree una nueva actividad</button>                    
                </div>
            </form-item>
        </div>
        <!-- ART -->
        <div class="form-row">
            <form-item col="col-6" label="ART" :errors="errors.art">
                <div class="col">
                    <input  type="text" name="art" class="form-control"
                            v-model="values.art">
                </div>
            </form-item>
        </div>
        <!-- PBIP -->
        <div class="form-row">
            <form-item col="col-6" label="Vencimiento PBIP" :errors="errors.pbip">
                <div class="col">
                    <input  type="date" name="pbip" class="form-control"
                            v-model="values.pbip">
                </div>
            </form-item>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        companies: {
            required: true
        },
        activities: {
            required: true
        }
    },
    data: () => {
        return {
            errors: {},
            values: {
                company_id: '',
                activity_id: '',
                art: '',
                pbip: '',
            }
        };
    },
    methods: {
        save: function() {
            axios.post('person-creation/working-information', this.values)
            .then(response => {
                this.errors = {};
                this.$parent.$emit('working-information-saved', true);
            })
            .catch(error => {
                this.errors = error.response.data.errors;
                this.$parent.$emit('working-information-saved', false);
            })
        }
    },
    computed: {
        company_id: function() {
            return this.values.company_id;
        },
    },
    watch: {
        company_id: function() {
            this.$parent.$emit('company-id-changed', this.company_id);
        },
    }
}
</script>
