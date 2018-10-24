<template>
    <index-wrapper :updating="updating" @advanced-search-submit="paginate(1)" @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Razón social" v-model="filters.business_name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="filters.name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Rubro" v-model="filters.area">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="CUIT" v-model="filters.cuit">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <small>Vencimiento (desde):</small>
                    <date-picker size="small" :value="filters.expiration_from" @input="value => filters.expiration_from = value"/>
                </div>
                <div class="col-12 col-md-6">
                    <small>Vencimiento (hasta):</small>
                    <date-picker size="small" :value="filters.expiration_until" @input="value => filters.expiration_until = value"/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="companies" @rowclicked="showProfile"/>
            <paginator-links v-if="companies.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
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
            filters: {
                business_name:  "",
                name:           "",
                area:           "",
                cuit:           "",
                expiration_from:"",
                expiration_until:""
            },
        }
    },
    mounted() {
        this.paginate(1);
    },
    computed: {
        /**
         * Returns whether the list of companies is being updated or not.
         */
        updating: function() { 
            return this.$store.getters.companies.updating
        },
        /**
         * Returns the groups of the current page.
         */
        companies: function() {
            return this.$store.getters.companies.paginator.data;
        },
        /**
         * Returns the current and the last page of the pagination.
         */
        paginator: function() {
            return {
                current_page: this.$store.getters.companies.paginator.current_page,
                last_page: this.$store.getters.companies.paginator.last_page
            };
        }
     },
    methods: {
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'companies', page: page, filters: this.filters});
        },
        /**
         * Redirects to the profile of the clicked person.
         */
        showProfile: function(company) {
            this.$router.push(`/companies/show/${company.id}`);
        },
        /**
         * Restarts filters and asks to the server for the first page.
         */
        advancedSearchClear: function() {
            this.filters = {
                business_name:      "",
                name:               "",
                area:               "",
                cuit:               "",
                expiration_from:    "",
                expiration_until:   ""
            };
            this.paginate(1);
        }
    }
}
</script>