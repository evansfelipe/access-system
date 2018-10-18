<template>
    <div>
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
            <form-item col="col-3" label="Entrada" :errors="errors.zone_id">
                <div class="col">
                    <select2    name="zone_id" :value="values.zone_id" @input="(value) => update({name: 'zone_id', value: value})"
                                placeholder="Seleccione una entrada" :options="zones"/>
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
        companyname: {
            required: true,
            type: String
        },
        zones: {
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
            let zone = this.zones.getById(this.values.zone_id);
            let range = `(${this.values.start} - ${this.values.end})`
            return  (this.companyname ? this.companyname + ' ' : '') +
                    (zone ? zone.text + ' ' : '') +
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

