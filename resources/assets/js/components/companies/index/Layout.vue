<template>
    <div>
        <div class="row text-right">
            <div class="col">
                <button class="btn btn-link btn-sm" @click="advancedSearch">
                    <template v-if="!advanced_search"><i class="fas fa-angle-down"></i> Mostrar</template>
                    <template v-else><i class="fas fa-angle-up"></i> Ocultar</template>
                    búsqueda avanzada
                </button>
            </div>
        </div>
        <transition name="collapse">
            <div v-show="advanced_search" class="card">
                <div class="card-body" style="background: #fafafa">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.business_name" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Razón social">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.name" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Nombre">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.area" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Rubro">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.cuit" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="CUIT">
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body" :style="updating ? 'min-height: 60vh' : ''">
                <loading-cover v-if="updating"/>
                <custom-table  v-else :columns="columns" :rows="companies" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
            </div>
        </div>
    </div>
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