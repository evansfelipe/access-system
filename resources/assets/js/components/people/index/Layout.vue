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
                <div class="col col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Apellido" v-model="filters.last_name">
                </div>
                <div class="col col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="filters.name">
                </div>
                <div class="col col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Documento" v-model="filters.document_number">
                </div>
            </div>
            <div class="form-row">
                <div class="col col-xl-4">
                    <input type="text" class="form-control form-control-sm" placeholder="CUIL / CUIT" v-model="filters.cuil">
                </div>
                <div class="col col-xl-4">
                    <select2    :value="filters.risk" @input="value => filters.risk = value"
                                placeholder="Nivel de riesgo" :options="risks" size="small"/>
                </div>
                <div class="col col-xl-4">
                    <remote-select2 size="small" :value="filters.company_id" path="/selects/companies" multiple
                                    @input="value => filters.company_id = value" placeholder="Empresa"/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :updating="updating" :columns="columns" :rows="paginator.data" @rowclicked="showProfile"/>
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
                { name: 'last_name',         text: 'Apellido',   width:'20' },
                { name: 'name',              text: 'Nombre',     width:'20' },
                { name: 'document_number',   text: 'Documento',  width:'20' },
                { name: 'company_name',      text: 'Empresa',    width:'40' }
            ],
            filters: {
                last_name:          '',
                name:               '',
                document_number:    '',
                cuil:               '',
                risk:               '',
                group_id:           [],
                company_id:         [],
            },
        }
    },
    beforeMount() {
        this.paginate(1);
    },
    computed: {
        /**
         * 
         */
        updating: function() {
            return  this.$store.getters.people.updating;
        },
        /**
         * Returns the current and the last page of the pagination.
         */
        paginator: function() {
            return this.$store.getters.people.paginator;
        },
        /**
         * 
         */
        risks: function() {
            return this.$store.getters.static_lists.risks.asOptions();
        },
    },
    methods: {
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'people', page: page, filters: this.filters});
        },
        /**
         * 
         */
        showProfile: function(person) {
            this.$router.push(`/people/show/${person.id}`);
        },
        /**
         * 
         */
        advancedSearchClear: function() {
            this.filters = {
                last_name:          '',
                name:               '',
                document_number:    '',
                cuil:               '',
                risk:               '',
                group_id:           [],
                company_id:         [],
            };
            this.paginate(1);
        }
    }
}
</script>