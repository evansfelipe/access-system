<template>
    <div>
        <div v-if="!loading" class="form-row mb-2">
            <div class="col-6">
                <select2 placeholder="Seleccionar zona" :value="zone_id" @input="value => zone_id = value" :options="zones_as_options" size="small"/>
            </div>
            <div class="col-6">
                <input type="text" v-model="filter" :disabled="!zone_id" class="form-control form-control-sm float-right" placeholder="Filtrar puerta">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <editable-list
                    v-if="loading || zone_id"

                    :loading="loading" :items="filtered_gates" :data="data"
                    
                    @create="focus" @save-create-success="fetch"

                    @edit="focus" @save-edit-success="fetch"
                >
                    <template slot="show" slot-scope="{item}">
                        <div class="row">
                            <div class="col">
                                <span>{{ item.name }}</span> <small>{{ !item.enabled ? 'No habilitada' : '' }}</small>
                            </div>
                        </div>
                    </template>

                    <template slot="input" slot-scope="{values, errors}">
                        <div class="row">
                            <form-item col="col-12" :errors="errors['name'] ? errors['name'] : []" extra-style="margin-bottom: 0px;">
                                <div class="col-7">
                                    <input v-model="values.name" 
                                            type="text" ref="input"
                                            class="md-input" style="width: 100%; padding: 0"
                                            placeholder="Nombre"
                                    >
                                </div>
                                <div class="col-5">
                                    <switch-box :value="values.enabled ? true : false"
                                                @update="value => values.enabled = value"
                                                label="Habilitada"
                                    />
                                </div>
                            </form-item>
                        </div>
                    </template>
                </editable-list>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'editable-list': require('./EditableList.vue')
    },
    props: {
        zones: {
            type:       Array,
            required:   true
        },
        gates: {
            type:       Array,
            required:   true
        },
        loading: {
            type:       Boolean, 
            required:   true
        }
    },
    data() {
        return {
            zone_id: '',
            filter: '',
            data: {
                create: '/settings/new-gate',
                edit:   '/settings/update-gate/:id',
                model:  { name: '', enabled: true, zone_id: null }
            }
        };
    },
    computed: {
        zones_as_options: function() {
            return this.zones.map(a => { return { id: a.id, text: a.name }});
        },
        filtered_gates: function() {
            return this.zone_id ? this.gates.filter(gate => {
                return (gate.zone_id === this.zone_id) &&  gate.name.matches(this.filter);
            }) : [];
        }
    },
    methods: {
        focus: function() {
            this.filter = '';
            this.$nextTick(() => this.$refs['input'].focus());
        },
        fetch: function(item) {
            this.$store.commit('addItemToList', {list: 'gates', item: item});
        }
    },
    watch: {
        zone_id: function(value) {
            this.data.model.zone_id = value;
        }
    }
}
</script>
