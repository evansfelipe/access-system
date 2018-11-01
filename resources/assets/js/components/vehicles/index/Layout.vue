<style lang="scss" scoped>
    div.form-row + div.form-row {
        margin-top: 5px;
    }
</style>

<template>
    <index-wrapper @advanced-search-submit="commitFilters" @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <remote-select2 size="small" :value="tempFilters.type_id" path="/selects/vehicle-types" multiple
                                    @input="value => tempFilters.type_id = value" placeholder="Tipo de vehículo"/>
                </div>
                <div class="col-12 col-md-12 col-xl-6">
                    <remote-select2 size="small" :value="tempFilters.company_id" path="/selects/companies" multiple
                                    @input="value => tempFilters.company_id = value" placeholder="Empresa"/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Titular" v-model="tempFilters.owner">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Patente" v-model="tempFilters.plate">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Color" v-model="tempFilters.colour">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Marca" v-model="tempFilters.brand">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Modelo" v-model="tempFilters.model">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Año" v-model="tempFilters.year">
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <remote-custom-table    list="vehicles" 
                                    :columns="columns"
                                    :filters="filters"
                                    @rowclicked="showProfile"
                                    :paginate-on-mounted="true"
                                    :wildcard="false"
            />
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                { name: 'plate',        text: 'Patente', width: '15' },
                { name: 'brand',        text: 'Marca',   width: '20' },
                { name: 'model',        text: 'Modelo',  width: '20' },
                { name: 'year',         text: 'Año',     width: '10' },
                { name: 'company_name', text: 'Empresa', width: '35' },
            ],
            filters: {},
            tempFilters: {
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
    methods: {
        /**
         * Redirects to the profile of the clicked vehicle.
         */
        showProfile: function(vehicle) {
            this.$router.push(`/vehicles/show/${vehicle.id}`);
        },
        /**
         * Restarts filters and pagination.
         */
        advancedSearchClear: function() {
            this.tempFilters = {
                plate:        "",
                brand:        "",
                model:        "",
                year:         "",
                colour:       "",
                owner:        "",
                company_id:   [],
                type_id:      [],
            };
            this.commitFilters();
        },
        /**
         * 
         */
        commitFilters: function() {
            this.filters = {
                plate:        this.tempFilters.plate,
                brand:        this.tempFilters.brand,
                model:        this.tempFilters.model,
                year:         this.tempFilters.year,
                colour:       this.tempFilters.colour,
                owner:        this.tempFilters.owner,
                company_id:   this.tempFilters.company_id,
                type_id:      this.tempFilters.type_id,
            };
        }
    }
}
</script>