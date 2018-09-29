<template>
    <div>
        <div v-if="!loading" class="row mb-2">
            <div class="col">
                <input v-model="filter" type="text" class="form-control form-control-sm float-right" placeholder="Filtrar por nombre de actividad">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <editable-list
                    :loading="loading" :items="filtered_vehicle_types" :data="data"
                    
                    @create="focus" @save-create-success="fetch"

                    @edit="focus" @save-edit-success="fetch"
                >
                    <template slot="show" slot-scope="{item}">
                        <div class="row">
                            <div class="col">
                                <span>{{ item.type }} <small>{{ item.allows_container ? '- Contenedores permitidos' : '' }}</small></span> 
                            </div>
                        </div>
                    </template>

                    <template slot="input" slot-scope="{values, errors}">
                        <div class="row">
                            <form-item :errors="[].concat(errors['type'] || []).concat(errors['allows_container'] || [])" extra-style="margin-bottom: 0px;">
                                <div class="col-7">
                                    <input  v-model="values.type" 
                                            type="text" ref="input"
                                            class="md-input" style="width: 100%; padding: 0"
                                            placeholder="Tipo"
                                    >
                                </div>
                                <div class="col-5">
                                    <switch-box :value="values.allows_container ? true : false"
                                                @update="value => values.allows_container = value"
                                                label="Contenedores"
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
        loading: {
            type:     Boolean,
            required: true
        },
        vehicleTypes: {
            type:     Array,
            required: true
        }
    },
    data() {
        return {
            filter: '',
            data: {
                create: '/settings/new-vehicle-type',
                edit:   '/settings/update-vehicle-type/:id',
                model:  { type: '', allows_container: false },
            }
        };
    },
    computed: {
        filtered_vehicle_types: function() {
            return this.vehicleTypes.filter(type => type.type.matches(this.filter));
        }
    },
    methods: {
        focus: function() {
            this.filter = '';
            this.$nextTick(() => this.$refs['input'].focus());
        },
        fetch: function(item) {
            this.$store.commit('addItemToList', {list: 'vehicle_types', item: item});
        }
    }
}
</script>
