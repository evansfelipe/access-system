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
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Razón social" v-model="tempFilters.business_name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Nombre" v-model="tempFilters.name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Rubro" v-model="tempFilters.area">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="CUIT" v-model="tempFilters.cuit">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <date-picker size="small" :value="tempFilters.expiration_from" @keyup.enter="commitFilters" @input="value => tempFilters.expiration_from = value" placeholder="Vencimiento (desde)"/>
                </div>
                <div class="col-12 col-md-6">
                    <date-picker size="small" :value="tempFilters.expiration_until" @keyup.enter="commitFilters" @input="value => tempFilters.expiration_until = value" placeholder="Vencimiento (hasta)"/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <remote-custom-table    list="companies" 
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
                { name: 'business_name', text: 'Razón social',   width: '30' },
                { name: 'name',          text: 'Nombre',         width: '30' },
                { name: 'area',          text: 'Rubro',          width: '20' },
                { name: 'cuit',          text: 'CUIT',           width: '20' }
            ],
            filters: {},
            tempFilters: {
                business_name:    "",
                name:             "",
                area:             "",
                cuit:             "",
                expiration_from:  "",
                expiration_until: ""
            },
        }
    },
    methods: {
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
            this.tempFilters = {
                business_name:      "",
                name:               "",
                area:               "",
                cuit:               "",
                expiration_from:    "",
                expiration_until:   ""
            };
            this.commitFilters();
        },
        /**
         * 
         */
        commitFilters: function() {
            this.filters = {
                business_name:      this.tempFilters.business_name,
                name:               this.tempFilters.name,
                area:               this.tempFilters.area,
                cuit:               this.tempFilters.cuit,
                expiration_from:    this.tempFilters.expiration_from,
                expiration_until:   this.tempFilters.expiration_until
            };
        }
    }
}
</script>