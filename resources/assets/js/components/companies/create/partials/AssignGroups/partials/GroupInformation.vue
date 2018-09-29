<template>
    <div class="form-row">
        <form-item col="col-5" label="Nombre" :errors="errors.name">
            <div class="col">
                <input  type="text" class="form-control" :placeholder="name_placeholder"
                        :value="values.name" @input="(e) => update({name: 'name', value: e.target.value})"
                >
            </div>
            <div v-if="!values.name && name_placeholder" class="col-12">
                <small>Mostrando nombre por defecto.</small>
            </div>
        </form-item>
        <form-item col="col-3" label="Entrada" :errors="errors.gate_id">
            <div class="col">
                <select2    name="gate_id" :value="values.gate_id" @input="(value) => update({name: 'gate_id', value: value})"
                            placeholder="Seleccione una entrada" :options="gates"/>
            </div>
        </form-item>
        <form-item col="col-4" label="Franja horaria" :errors="[].concat(errors.start ? errors.start : []).concat(errors.end ? errors.end : [])">
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
</template>

<script>
export default {
    props: {
        companyname: {
            required: true,
            type: String
        },
        gates: {
            required: true,
            type: Array
        },
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
                end: '23:30'
            }
        };
    },
    computed: {
        name_placeholder: function() {
            let gate = this.gates.getById(this.values.gate_id);
            let range = `(${this.values.start} - ${this.values.end})`
            return  (this.companyname ? this.companyname + ' ' : '') +
                    (gate ? gate.text + ' ' : '') +
                    (this.values.start && this.values.end ? range : '');
        },
        different_days: function() {
            return this.values.end && this.values.start && this.values.end < this.values.start;
        }
    },
    methods: {
        update: function({name, value}) {
            this.$emit('change', {attribute: name, value});
        }
    }
}
</script>

