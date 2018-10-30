<style lang="scss" scoped>
    div.form-row + div.form-row {
        margin-top: 5px;
    }
</style>

<template>
    <index-wrapper @advanced-search-submit="paginate(1)" @advanced-search-clear="advancedSearchClear">
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
                    <date-picker size="small" :value="filters.expiration_from" @input="value => filters.expiration_from = value" placeholder="Vencimiento (desde)"/>
                </div>
                <div class="col-12 col-md-6">
                    <date-picker size="small" :value="filters.expiration_until" @input="value => filters.expiration_until = value" placeholder="Vencimiento (hasta)"/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :updating="updating" :columns="columns" :rows="paginator.data" @rowclicked="showProfile" @sort="sortHandler"/>
            <br v-if="paginator.data.length > 0">
            <paginator-links v-if="paginator.data.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                { name: 'business_name', text: 'Razón social',   width: '30' },
                { name: 'name',          text: 'Nombre',         width: '30' },
                { name: 'area',          text: 'Rubro',          width: '20' },
                { name: 'cuit',          text: 'CUIT',           width: '20' }
            ],
            filters: {
                business_name:    "",
                name:             "",
                area:             "",
                cuit:             "",
                expiration_from:  "",
                expiration_until: ""
            },
            sort: {
                column: null,
                order:  null,
            }
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
         * Returns the companies paginator.
         */
        paginator: function() {
            return this.$store.getters.companies.paginator;
        }
     },
    methods: {
        /**
         * Paginates the companies using the new sort condition.
         */
        sortHandler: function(sort) {
            this.sort = sort;
            this.paginate(1);
        },
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'companies', page: page, filters: this.filters, sort: this.sort});
        },
        /**
         * Redirects to the profile of the clicked person.
         */
        showProfile: function(company) {
            this.$router.push(`/companies/show/${company.id}`);
        },
        /**
         * Restarts filters and pagination.
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