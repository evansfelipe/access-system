<style lang="scss" scoped>
    .btn-list {
        width: 100%;
        background-color: transparent;
        border: 1px solid grey;

        &:disabled {
            color: grey;
        }

        &.selected {
            border-color: #3F729B;
            color: #3F729B;
            font-weight: bold;
        }
    }
</style>

<template>
    <div>
        <!-- Options buttons -->
        <div class="form-row mb-3">
            <!-- Company vehicles list button -->
            <div class="col-4">
                <button :disabled="assignedCompanies.length <= 0" type="button" :class="`btn btn-list ${selected_list === 'company' ? 'selected' : ''}`" @click="getCompanyVehicles">
                    {{ assignedCompanies.length > 1 ? 'Empresas seleccionadas' : 'Empresa seleccionada' }}
                </button>
            </div>
            <!-- Others vehicles list button -->
            <div class="col-4">
                <button type="button" :class="`btn btn-list ${selected_list === 'other' ? 'selected' : ''}`" @click="getOtherVehicles">
                    Otras empresas
                </button>
            </div>
            <!-- Selected vehicles list button -->
            <div class="col-4">
                <button :disabled="vehicles_picked.length <= 0" type="button" :class="`btn btn-list ${selected_list === 'picked' ? 'selected' : ''}`" @click="getPickedVehicles">
                    <span class="badge badge-dark ml-1">{{ vehicles_picked.length }}</span> Seleccionados
                </button>
            </div>
        </div>
        <!-- List -->
        <div class="row">
            <div class="col">
                <custom-table
                    :updating="updating"
                    :columns="columns"
                    :rows="paginator.data"
                    :pickable="{ active: true, list: vehicles_picked }"
                    @rowclicked="toggleVehicle"
                    @sort="sortHandler"
                />
            </div>
        </div>
        <!-- Pagination and Search -->
        <div class="row mt-3">
            <div class="col-6">
                <search-input :updating="updating" @input="searchHandler" placeholder="Filtrar columnas"/>
            </div>
            <div class="col-6">
                <paginator-links v-if="paginator.data.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        assignedCompanies: { 
            required: false,
            type: Array,
            default: []
        },
    },
    data: function() {
        return {
            selected_list: '',
            search: '',
            columns: [ 
                { name: 'plate',    text: 'Patente' },
                { name: 'brand',    text: 'Marca'   },
                { name: 'model',    text: 'Modelo'  },
                { name: 'year',     text: 'Año'     },
                { name: 'colour',   text: 'Color'   },
            ],
            filters: {
                wildcard:       '',
                not_company_id: [],
                company_id:     [],
                id:             []
            },
            sort: {
                column: null,
                order:  null,
            }
        };
    },
    mounted() {
        this.getOtherVehicles();
    },
    computed: {
        /**
         * 
         */
        updating: function() {
            return this.$store.getters.vehicles.updating;
        },
        /**
         * 
         */
        vehicles_picked: function() {
            return this.$store.getters.person.values.assign_vehicles.vehicles_id;
        },
        /**
         * 
         */
        paginator: function() {
            return this.$store.getters.vehicles.paginator;
        },
    }, 
    methods: {
        /**
         * Paginates the people using the new sort condition.
         */
        sortHandler: function(sort) {
            this.sort = sort;
            this.paginate(1);
        },
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'vehicles', page, filters: this.filters, sort: this.sort});
        },
        /**
         * 
         */
        searchHandler: function(value) {
            this.search = value;
            switch (this.selected_list) {
                case 'company':
                    this.getCompanyVehicles();
                    break;
                case 'other':
                    this.getOtherVehicles();
                    break;
                case 'picked':
                    this.getPickedVehicles();
                    break;
            }
        },
        /**
         * 
         */
        resetFilters: function() {
            this.filters = {
                wildcard:       this.search,
                not_company_id: [],
                company_id:     [],
                id:             []
            };
        },
        /**
         * 
         */
        getCompanyVehicles: function() {
            this.selected_list = 'company';
            this.resetFilters();
            this.filters.company_id = this.assignedCompanies;
            this.paginate(1);
        },
        /**
         * 
         */
        getOtherVehicles: function() {
            this.selected_list = 'other';
            this.resetFilters();
            this.filters.not_company_id = this.assignedCompanies;
            this.paginate(1);
        },
        /**
         * 
         */
        getPickedVehicles: function() {
            this.selected_list = 'picked';
            this.resetFilters();
            this.filters.id = this.vehicles_picked;
            this.paginate(1);
        },
        /**
         * 
         */
        toggleVehicle(vehicle) {
            this.$store.commit('pickVehicle', vehicle.id);
        }
    },
    watch: {
        assignedCompanies: {
            handler: function(newValue) {
                switch(this.selected_list) {
                    case 'company':
                        if(newValue.length > 0)
                            this.getCompanyVehicles();
                        else
                            this.getOtherVehicles();
                        break;
                    case 'other':
                        this.getOtherVehicles();
                        break;
                    case 'picked':
                        this.getPickedVehicles();
                        break;
                }
            },
            deep: true
        }
    }
}
</script>
