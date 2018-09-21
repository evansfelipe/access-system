<template>
    <index-wrapper :updating="updating" @advanced-search="advancedSearch">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="col-12 col-md-2">
                <input type="text" class="form-control form-control-sm" placeholder="Patente" v-model="filter.conditions.plate">
            </div>
            <div class="col-12 col-md-2">
                <input type="text" class="form-control form-control-sm" placeholder="Marca" v-model="filter.conditions.brand">
            </div>
            <div class="col-12 col-md-2">
                <input type="text" class="form-control form-control-sm" placeholder="Modelo" v-model="filter.conditions.model">
            </div>
            <div class="col-12 col-md-2">
                <input type="text" class="form-control form-control-sm" placeholder="Año" v-model="filter.conditions.year">
            </div>
            <div class="col-12 col-md-4">
                <input type="text" class="form-control form-control-sm" placeholder="Nombre de la empresa" v-model="filter.conditions.company_name">
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="vehicles" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
        </template>
    </index-wrapper>
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