<style lang="scss" scoped>
    div.form-row + div.form-row {
        margin-top: 5px;
    }
</style>

<template>
    <index-wrapper :updating="updating" @advanced-search-submit="paginate(1)" @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <remote-select2 size="small" :value="filters.type_id" path="/selects/vehicle-types" multiple
                                    @input="value => filters.type_id = value" placeholder="Tipo de vehículo"/>
                </div>
                <div class="col-12 col-md-12 col-xl-6">
                    <remote-select2 size="small" :value="filters.company_id" path="/selects/companies" multiple
                                    @input="value => filters.company_id = value" placeholder="Empresa"/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Titular" v-model="filters.owner">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Patente" v-model="filters.plate">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Color" v-model="filters.colour">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Marca" v-model="filters.brand">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Modelo" v-model="filters.model">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Año" v-model="filters.year">
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="vehicles" @rowclicked="showProfile"/>
            <paginator-links v-if="vehicles.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
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
            filters: {
                plate:        "",
                brand:        "",
                model:        "",
                year:         "",
                colour:       "",
                owner:        "",
                company_id:   [],
                type_id:      [],
            },
        }
    },
    beforeMount() {
        this.paginate(1);
    },
    computed: {
        updating: function() { 
            return  this.$store.getters.vehicles.updating;
        },
        /**
         * Returns the vehicles of the current page.
         */
        vehicles: function() {
            return this.$store.getters.vehicles.paginator.data;
        },
        /**
         * Returns the current and the last page of the pagination.
         */
        paginator: function() {
            return {
                current_page: this.$store.getters.vehicles.paginator.current_page,
                last_page: this.$store.getters.vehicles.paginator.last_page
            };
        },
    },
    methods: {
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'vehicles', page: page, filters: this.filters});
        },
        /**
         * Redirects to the profile of the clicked vehicle.
         */
        showProfile: function(vehicle) {
            this.$router.push(`/vehicles/show/${vehicle.id}`);
        },
        /**
         * Restarts filters and asks to the server for the first page.
         */
        advancedSearchClear: function() {
            this.filters = {
                plate:        "",
                brand:        "",
                model:        "",
                year:         "",
                colour:       "",
                owner:        "",
                company_id:   [],
                type_id:      [],
            };
            this.paginate(1);
        }
    }
}
</script>