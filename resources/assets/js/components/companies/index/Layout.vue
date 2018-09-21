<template>
    <index-wrapper :updating="updating" @advanced-search="advancedSearch">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Razón social" v-model="filter.conditions.business_name">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="filter.conditions.name">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Rubro" v-model="filter.conditions.area">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="CUIT" v-model="filter.conditions.cuit">
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="companies" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                {name: 'business_name', text: 'Razón social',   width: '30'},
                {name: 'name',          text: 'Nombre',         width: '30'},
                {name: 'area',          text: 'Rubro',          width: '20'},
                {name: 'cuit',          text: 'CUIT',           width: '20'}
            ],
            filter: {
                strict: true,
                conditions: {
                    business_name: "",
                    name:          "",
                    area:          "",
                    CUIT:          ""
                }
            },
            companies: [],
            advanced_search: false
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'companies');
        this.companies = this.$store.getters.companies.list;
    },
    computed: {
        /**
         * Returns whether the list of companies is being updated or not.
         */
        updating: function() { return this.$store.getters.companies.updating },
        /**
         * Returns the list of companies from the store.
         */
        unfilteredCompanies: function() { return this.$store.getters.companies.list }
    },
    watch: {
        /**
         * When the unfiltered list of companies changes, resets the companies list and the filters.
         */
        unfilteredCompanies: function() { 
            this.companies = this.unfilteredCompanies;
            this.filter = {
                strict: true,
                conditions: { business_name: "", name: "", area: "", cuit: "" }
            }
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked person.
         */
        showProfile: function(company) {
            this.$router.push(`/companies/show/${company.id}`);
        },
        /**
         * Restarts filters and show/hide advanced search.
         */
        advancedSearch: function() {
            this.advanced_search = !this.advanced_search;
            if( this.advanced_search === true ) {
                this.filter.conditions = { business_name: "", name: "", area: "", cuit: "" };
            }
        }
    }
}
</script>