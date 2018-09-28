<style lang="scss" scoped>    
    .dropdown-toggle::after {
        display:none;
    }

    .btn-list, .btn-list:disabled {
        background-color: transparent;
        font-weight: bold;
        border: 1px solid grey;
        color: grey;
    }

    .btn-list.selected {
        border-color: #3F729B;
        color: #3F729B;
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.containers.updating"/>
        <template v-else>
            <!-- Options buttons -->
            <div class="row text-center mb-3">
                <div class="col-12">
                    <div class="row">
                        <!-- All containers list button -->
                        <div class="col-6">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'all' ? 'selected' : ''}`"
                                    @click="selected_list = 'all'"
                                    :disabled="containers.length <= 0">
                                <span class="badge badge-dark ml-1">{{ containers.length }}</span> Todos
                            </button>
                        </div>
                        <!-- Selected containers list button -->
                        <div class="col-6">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'picked' ? 'selected' : ''}`"
                                    @click="selected_list = 'picked'"
                                    :disabled="containers_picked.length <= 0">
                                <span class="badge badge-dark ml-1">{{ containers_picked.length }}</span> Seleccionados
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Options buttons -->
            <!-- Containers list -->
            <div class="row">
                <div class="col-12 table-container">
                    <custom-table
                        :columns="columns"
                        :rows="containers_list"
                        :pickable="{
                            active: true,
                            list: containers_picked
                        }"
                        @rowclicked="toggleContainer"
                    />
                </div>
            </div>
            <!-- /Containers list -->
        </template>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            containers_list: [],
            selected_list: 'all',
            show_outdated: false,
            columns: [ 
                {name: 'series_number', text: 'Número de serie', width: '25'},
                {name: 'format',        text: 'Formato',         width: '25'},
                {name: 'brand',         text: 'Marca',           width: '25'},
                {name: 'model',         text: 'Modelo',          width: '25'}
            ]
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'containers');
        this.containers_list = this.containers;
    },
    computed: {
        containers: function() {
            return this.$store.getters.containers.list;
        },
        containers_picked: function() {
            return this.$store.getters.vehicle.values.assign_containers.containers_id;
        },
        picked_list: function() {
            let list = [];
            this.containers_picked.forEach(container_id => {
                list.push(this.$store.getters.containers.getById(container_id));
            });
            return list;
        }
    }, 
    methods: {
        toggleContainer(container) {
            this.$store.commit('pickContainer', container.id);
        }
    },
    watch: {
        selected_list: function(value) {
            console.log(this.containers);
            console.log(value);
            
            if( value === 'all' ) {
                this.containers_list = this.containers;
            }
            else if( value === 'picked' ) {
                this.containers_list = this.picked_list;
            }
            else {
                console.log('nope');
                
            }
        },
        containers: {
            handler: function() {
            this.containers_list = this.containers;
            },
            deep:true,
        }
    }
}
</script>