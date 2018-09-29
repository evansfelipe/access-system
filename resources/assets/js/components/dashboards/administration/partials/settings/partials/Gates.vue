<template>
    <div>
        <div v-if="!loading" class="row mb-2">
            <div class="col">
                <input v-model="filter" type="text" class="form-control form-control-sm float-right" placeholder="Filtrar por nombre">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <editable-list
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
            filter: '',
            data: {
                create: '/settings/new-gate',
                edit:   '/settings/update-gate/:id',
                model:  { name: '', enabled: true }
            }
        };
    },
    computed: {
        filtered_gates: function() {
            return this.gates.filter(gate => gate.name.matches(this.filter));
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
    }
}
</script>
