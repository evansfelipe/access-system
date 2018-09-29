<template>
    <div>
        <div v-if="!loading" class="form-row mb-3">
            <div class="col-6">
                <select2 placeholder="Seleccionar actividad" :value="activity_id" @input="value => activity_id = value" :options="activities_as_options" size="small"/>
            </div>
            <div class="col-6">
                <input type="text" v-model="filter" :disabled="!activity_id" class="form-control form-control-sm" placeholder="Filtrar subactividad">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <editable-list

                    v-if="loading || activity_id"

                    :loading="loading" :items="filtered_subactivities" :data="data"
                    
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
                                <div class="col-12">
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
        activities: {
            type: Array,
            required: true
        },
        subactivities: {
            type: Array,
            required: true
        },
        loading: {
            type: Boolean, 
            required: true
        }
    },
    data() {
        return {
            activity_id: '',
            filter: '',
            data: {
                create: '/settings/new-subactivity/',
                edit:   '/settings/update-subactivity/:id',
                model:  { name: '', activity_id: null }
            }
        };
    },
    computed: {
        activities_as_options: function() {
            return this.activities.map(a => { return { id: a.id, text: a.name }});
        },
        filtered_subactivities: function() {
            return this.activity_id ? this.subactivities.filter(subactivity => {
                return (subactivity.activity_id === this.activity_id) &&  subactivity.name.matches(this.filter);
            }) : [];
        }
    },
    methods: {
        focus: function() {
            this.filter = '';
            this.$nextTick(() => this.$refs['input'].focus());
        },
        fetch: function(item) {
            this.$store.commit('addItemToList', {list: 'subactivities', item: item});
        }
    },
    watch: {
        activity_id: function(value) {
            this.data.model.activity_id = value;
        }
    }
}
</script>

