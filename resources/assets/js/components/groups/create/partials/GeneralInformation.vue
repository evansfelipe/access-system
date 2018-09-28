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
                <div class="col-12" v-if="two_days">
                    <small>Esta franja comenzará un día y finalizará al siguiente.</small>
                </div>
            </form-item>
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
            value2: '',
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
    },
    computed: {
        companies: function() {
            return this.$store.getters.companies.list.map(company => {
                return {
                    id: company.id,
                    text: company.name,
                };
            });
        },
        gates: function() {
            return this.$store.getters.gates.list.map(gate => {
                return {
                    id: gate.id,
                    text: gate.name,
                };
            });
        },
        name_placeholder: function() {
            let company = this.companies.getById(this.values.company_id);
            let gate = this.gates.getById(this.values.gate_id);
            let range = `(${this.values.start} - ${this.values.end})`
            return  (company ? company.text + ' - ' : '') +
                    (gate ? gate.text + ' ' : '') +
                    (this.values.start && this.values.end ? range : '');
        },
        two_days: function() {
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
