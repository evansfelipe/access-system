<template>
    <div>
        <div v-if="!loading" class="form-row mb-2">
            <div class="col">
                <input type="text" v-model="filter" class="form-control form-control-sm float-right" placeholder="Filtrar zona">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <editable-list
                    :loading="loading" :items="filtered_zones" :data="data"
                    
                    @create="focus" @save-create-success="fetch"

                    @edit="focus" @save-edit-success="fetch"
                >
                    <template slot="show" slot-scope="{item}">
                        <div class="row">
                            <div class="col">
                                <span>{{ item.name }}</span>
                            </div>
                        </div>
                    </template>

                    <template slot="input" slot-scope="{values, errors}">
                        <div class="row">
                            <form-item col="col-12" :errors="errors['name'] ? errors['name'] : []" extra-style="margin-bottom: 0px;">
                                <div class="col">
                                    <input v-model="values.name" 
                                            type="text" ref="input"
                                            class="md-input" style="width: 100%; padding: 0"
                                            placeholder="Nombre"
                                    >
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
        loading: {
            type:       Boolean, 
            required:   true
        }
    },
    data() {
        return {
            filter: '',
            data: {
                create: '/settings/new-zone',
                edit:   '/settings/update-zone/:id',
                model:  { name: '' }
            }
        };
    },
    computed: {
        filtered_zones: function() {
            return this.zones.filter(zone => zone.name.matches(this.filter));
        }
    },
    methods: {
        focus: function() {
            this.filter = '';
            this.$nextTick(() => this.$refs['input'].focus());
        },
        fetch: function(item) {
            this.$store.commit('addItemToList', {list: 'zones', item: item});
        }
    }
}
</script>
