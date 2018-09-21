<template>
    <div>
        <div class="row text-right">
            <div class="col">
                <button class="btn btn-link btn-sm" @click="advancedSearch">
                    <template v-if="!advanced_search"><i class="fas fa-angle-down"></i> Mostrar</template>
                    <template v-else><i class="fas fa-angle-up"></i> Ocultar</template>
                    búsqueda avanzada
                </button>
            </div>
        </div>
        <transition name="collapse">
            <div v-show="advanced_search" class="card">
                <div class="card-body" style="background: #fafafa">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.series_number" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Número de serie">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.format" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Formato">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.brand" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Marca">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.model" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Modelo">
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body" :style="updating ? 'min-height: 60vh' : ''">
                <loading-cover v-if="updating"/>
                <custom-table  v-else :columns="columns" :rows="containers" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                {name: 'series_number', text: 'Número de serie', width: '25'},
                {name: 'format',        text: 'Formato',         width: '25'},
                {name: 'brand',         text: 'Marca',           width: '25'},
                {name: 'model',         text: 'Modelo',          width: '25'}
            ],
            filter: {
                strict: true,
                conditions: {
                    series_number: "",
                    format:        "",
                    brand:         "",
                    model:         ""
                }
            },
            containers: [],
            advanced_search: false
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'containers');
        this.containers = this.$store.getters.containers.list;
    },
    computed: {
        /**
         * Returns whether the list of containers is being updated or not.
         */
        updating: function() { return this.$store.getters.containers.updating },
        /**
         * Returns the list of containers from the store.
         */
        unfilteredContainers: function() { return this.$store.getters.containers.list }
    },
    watch: {
        /**
         * When the unfiltered list of containers changes, resets the containers list and the filters.
         */
        unfilteredContainers: function() { 
            this.containers = this.unfilteredContainers;
            this.filter = {
                strict: true,
                conditions: { 
                    series_number: "",
                    format:        "",
                    brand:         "",
                    model:         ""
                }
            }
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked container.
         */
        showProfile: function(container) {
            this.$router.push(`/containers/show/${container.id}`);
        },
        /**
         * Restarts filters and show/hide advanced search.
         */
        advancedSearch: function() {
            this.advanced_search = !this.advanced_search;
            if( this.advanced_search === true ) {
                this.filter.conditions = {
                    series_number: "",
                    format:        "",
                    brand:         "",
                    model:         ""
                };
            }
        }
    }
}
</script>