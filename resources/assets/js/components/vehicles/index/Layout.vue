<style lang="scss" scoped>
    div.form-row + div.form-row {
        margin-top: 5px;
    }
</style>

<template>
    <index-wrapper  :conditions="conditions" :updating="updating" advanced-search-route="/vehicles/id-search"
                    @advanced-search-success="advancedSearchSuccess"
                    @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <select2    size="small" :value="conditions.type_id" @input="value => conditions.type_id = value"
                                placeholder="Tipo de vehículo" :options="vehicle_types" multiple/>
                </div>
                <div class="col-12 col-md-12 col-xl-6">
                    <select2    size="small" :value="conditions.company_id" @input="value => conditions.company_id = value"
                                placeholder="Empresa" :options="companies" multiple/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Titular" v-model="conditions.owner">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Patente" v-model="conditions.plate">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Color" v-model="conditions.colour">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Marca" v-model="conditions.brand">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Modelo" v-model="conditions.model">
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Año" v-model="conditions.year">
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="vehicles" @rowclicked="showProfile"/>
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
            conditions: {
                plate:        "",
                brand:        "",
                model:        "",
                year:         "",
                colour:       "",
                owner:        "",
                company_id:   [],
                type_id:      [],
            },
            filtered_ids: null
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'vehicles');
        this.$store.dispatch('fetchList', 'companies');
        this.$store.dispatch('fetchList', 'vehicle_types');
    },
    computed: {
        updating: function() { 
            return  this.$store.getters.vehicles.updating ||
                    this.$store.getters.companies.updating ||
                    this.$store.getters.vehicle_types.updating;
        },

        vehicles: function() {
            let all = this.$store.getters.vehicles.list;
            return this.filtered_ids ? all.filter(vehicle => this.filtered_ids.includes(vehicle.id)) : all;
        },

        companies: function() {
            return this.$store.getters.companies.asOptions();
        },

        vehicle_types: function() {
            return this.$store.getters.vehicle_types.asOptions('type')
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked vehicle.
         */
        showProfile: function(vehicle) {
            this.$router.push(`/vehicles/show/${vehicle.id}`);
        },
        advancedSearchSuccess: function(ids) {
            this.filtered_ids = ids;
        },
        advancedSearchClear: function() {
            this.filtered_ids = null;
            this.conditions = {
                plate:        "",
                brand:        "",
                model:        "",
                year:         "",
                colour:       "",
                owner:        "",
                company_id:   [],
                type_id:      [],
            };
        }
    }
}
</script>