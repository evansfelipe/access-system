<template>
    <div>
        <loading-cover v-if="this.$store.getters.companies.updating || this.$store.getters.gates.updating"/>
        <div class="form-row">
            <form-item label="Empresa" :errors="errors.company_id">
                <div class="col">
                    <select2    name="company_id" :value="values.company_id" @input="(value) => update({name: 'company_id', value: value})"
                                placeholder="Seleccione una empresa" :options="companies"/>
                </div>
            </form-item>
            <form-item label="Nombre" :errors="errors.name">
                <div class="col">
                    <input  type="text" class="form-control" :placeholder="name_placeholder"
                            :value="values.name" @input="(e) => update({name: 'name', value: e.target.value})"
                    >
                </div>
                <div v-if="!values.name && name_placeholder" class="col-12">
                    <small>Mostrando nombre por defecto.</small>
                </div>
            </form-item>
        </div>
        <div class="form-row">
            <form-item label="Entrada" :errors="errors.gate_id">
                <div class="col">
                    <select2    name="gate_id" :value="values.gate_id" @input="(value) => update({name: 'gate_id', value: value})"
                                placeholder="Seleccione una entrada" :options="gates"/>
                </div>
            </form-item>
            <form-item label="Franja horaria" :errors="[].concat(errors.start ? errors.start : []).concat(errors.end ? errors.end : [])">
                <div class="col">
                      <el-time-select
                        placeholder="Comienzo"
                        :value="values.start"
                        @input="(value) => update({name: 'start', value: value})"
                        style="width: 100%;"
                        :picker-options="timer_options">
                    </el-time-select>
                </div>
                <div class="col">
                    <el-time-select
                        placeholder="Finalización"
                        :value="values.end"
                        @input="(value) => update({name: 'end', value: value})"
                        style="width: 100%;"
                        :picker-options="timer_options">
                    </el-time-select>
                </div>
                <div class="col-12" v-if="different_days">
                    <small>Esta franja comenzará un día y finalizará al siguiente.</small>
                </div>
            </form-item>
        </div>
        <div class="form-row">
            <div class="col">
                <hr-label>Días habilitados</hr-label>
                <switch-box class="mr-3" label="Lunes" :value="values.days.monday" @update="value => update({name: 'days.monday', value: value})"/>
                <switch-box class="mr-3" label="Martes" :value="values.days.tuesday" @update="value => update({name: 'days.tuesday', value: value})"/>
                <switch-box class="mr-3" label="Miércoles" :value="values.days.wednesday" @update="value => update({name: 'days.wednesday', value: value})"/>
                <switch-box class="mr-3" label="Jueves" :value="values.days.thursday" @update="value => update({name: 'days.thursday', value: value})"/>
                <switch-box class="mr-3" label="Viernes" :value="values.days.friday" @update="value => update({name: 'days.friday', value: value})"/>
                <switch-box class="mr-3" label="Sábado" :value="values.days.saturday" @update="value => update({name: 'days.saturday', value: value})"/>
                <switch-box class="mr-3" label="Domingo" :value="values.days.sunday" @update="value => update({name: 'days.sunday', value: value})"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        values: {
            required: true,
            type: Object,
        },
        errors: {
            required: true,
            type: Array,
        }
    },
    data() {
        return {
            timer_options: {
                start: '00:00',
                step: '00:30',
                end: '23:45'
            }
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList','companies');
        this.$store.dispatch('fetchList','gates');
        console.log(this.values.days);
        
        console.log(this.values.days.monday, this.values.days.tuesday);
    },
    computed: {
        companies: function() {
            return this.$store.getters.companies.asOptions();
        },
        gates: function() {
            return this.$store.getters.gates.asOptions();
        },
        name_placeholder: function() {
            let company = this.companies.getById(this.values.company_id);
            let gate = this.gates.getById(this.values.gate_id);
            let range = `(${this.values.start} - ${this.values.end})`
            return  (company ? company.text + ' - ' : '') +
                    (gate ? gate.text + ' ' : '') +
                    (this.values.start && this.values.end ? range : '');
        },
        different_days: function() {
            return this.values.end && this.values.start && this.values.end < this.values.start;
        }
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'group', properties_path: `values.general_information.${name}`, value: value });
        }
    }
}
</script>
