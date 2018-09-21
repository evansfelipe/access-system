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
                        <div class="col-12 col-md-2">
                            <input v-model="filter.conditions.plate" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Patente">
                        </div>
                        <div class="col-12 col-md-2">
                            <input v-model="filter.conditions.brand" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Marca">
                        </div>
                        <div class="col-12 col-md-2">
                            <input v-model="filter.conditions.model" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Modelo">
                        </div>
                        <div class="col-12 col-md-2">
                            <input v-model="filter.conditions.year" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Año">
                        </div>
                        <div class="col-12 col-md-4">
                            <input v-model="filter.conditions.company_name" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Nombre de la empresa">
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body" :style="updating ? 'min-height: 60vh' : ''">
                <loading-cover v-if="updating"/>
                <custom-table  v-else :columns="columns" :rows="vehicles" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                {name: 'plate',        text: 'Patente', width: '15'},
                {name: 'brand',        text: 'Marca',   width: '20'},
                {name: 'model',        text: 'Modelo',  width: '20'},
                {name: 'year',         text: 'Año',     width: '10'},
                {name: 'company_name', text: 'Empresa', width: '35'},
            ],
            filter: {
                strict: true,
                conditions: {
                    plate:        "",
                    brand:        "",
                    model:        "",
                    year:         "",
                    company_name: ""
                }
            },
            vehicles: [],
            advanced_search: false
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'vehicles');
        this.vehicles = this.$store.getters.vehicles.list;
    },
    computed: {
        /**
         * Returns whether the list of vehicles is being updated or not.
         */
        updating: function() { return this.$store.getters.vehicles.updating },
        /**
         * Returns the list of vehicles from the store.
         */
        unfilteredVehicles: function() { return this.$store.getters.vehicles.list }
    },
    watch: {
        /**
         * When the unfiltered list of vehicles changes, resets the vehicles list and the filters.
         */
        unfilteredVehicles: function() { 
            this.vehicles = this.unfilteredVehicles;
            this.filter = {
                strict: true,
                conditions: { 
                    plate:        "",
                    brand:        "",
                    model:        "",
                    year:         "",
                    company_name: "" 
                }
            }
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked vehicle.
         */
        showProfile: function(vehicle) {
            this.$router.push(`/vehicles/show/${vehicle.id}`);
        },
        /**
         * Restarts filters and show/hide advanced search.
         */
        advancedSearch: function() {
            this.advanced_search = !this.advanced_search;
            if( this.advanced_search === true ) {
                this.filter.conditions = {
                    plate:        "",
                    brand:        "",
                    model:        "",
                    year:         "",
                    company_name: ""
                };
            }
        }
    }
}
</script>