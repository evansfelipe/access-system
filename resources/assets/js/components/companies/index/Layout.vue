<template>
    <index-wrapper  :conditions="conditions" :updating="updating" advanced-search-route="/companies/id-search"
                    @advanced-search-success="advancedSearchSuccess"
                    @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Razón social" v-model="conditions.business_name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="conditions.name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Rubro" v-model="conditions.area">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="CUIT" v-model="conditions.cuit">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <small>Vencimiento (desde):</small>
                    <date-picker size="small" :value="conditions.expiration_from" @input="value => conditions.expiration_from = value"/>
                </div>
                <div class="col-12 col-md-6">
                    <small>Vencimiento (hasta):</small>
                    <date-picker size="small" :value="conditions.expiration_until" @input="value => conditions.expiration_until = value"/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="companies" @rowclicked="showProfile"/>
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
            conditions: {
                business_name:  "",
                name:           "",
                area:           "",
                cuit:           "",
                expiration_from:"",
                expiration_until:""
            },
            filtered_ids: null
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'companies');
    },
    computed: {
        updating: function() { 
            return this.$store.getters.companies.updating
        },
        companies: function() {
            let all = this.$store.getters.companies.list;
            return this.filtered_ids ? all.filter(company => this.filtered_ids.includes(company.id)) : all;
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked person.
         */
        showProfile: function(company) {
            this.$router.push(`/companies/show/${company.id}`);
        },
        advancedSearchSuccess: function(ids) {
            this.filtered_ids = ids;
        },
        advancedSearchClear: function() {
            this.filtered_ids = null;
            this.conditions = {
                business_name:  "",
                name:           "",
                area:           "",
                cuit:           "",
                expiration_from:"",
                expiration_until:""
            };
        }
    }
}
</script>