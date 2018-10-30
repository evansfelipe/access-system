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
                    <select2    :value="filters.sex" @input="value => filters.sex = value"
                                placeholder="Género" :options="sexes" size="small"/>
                </div>

            </div>
            <div class="form-row">
                <div class="col col-xl-6">
                    <remote-select2 size="small" :value="filters.company_id" path="/selects/companies" multiple
                                    @input="value => filters.company_id = value" placeholder="Empresa/s"/>
                </div>
                <div class="col col-xl-6">
                    <remote-select2 size="small" :value="filters.activity_id" path="/selects/activities" multiple
                                    @input="value => filters.activity_id = value" placeholder="Actividad/es"/>
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
                { name: 'last_name',         text: 'Apellido',      width:'20' },
                { name: 'name',              text: 'Nombre',        width:'20' },
                { name: 'document_number',   text: 'Documento',     width:'15' },
                { name: 'cuil',              text: 'CUIL / CUIT',   width:'15' },
                { name: 'company_name',      text: 'Empresa',       width:'30' }
            ],
            filters: {
                last_name:          '',
                name:               '',
                document_number:    '',
                cuil:               '',
                risk:               '',
                sex:                '',
                group_id:           [],
                company_id:         [],
                activity_id:        [],
            },
            sort: {
                column: null,
                order:  null,
            }
        }
    },
    beforeMount() {
        this.paginate(1);
    },
    computed: {
        /**
         * Returns whether the list of people is being updated or not.
         */
        updating: function() {
            return  this.$store.getters.people.updating;
        },
        /**
         * Returns the people paginator.
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
        /**
         * 
         */
        sexes: function() {
            return this.$store.getters.static_lists.sexes.asOptions();
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
            this.$store.dispatch('paginateList', {what: 'people', page: page, filters: this.filters, sort: this.sort});
        },
        /**
         * 
         */
        showProfile: function(person) {
            this.$router.push(`/people/show/${person.id}`);
        },
        /**
         * Restarts filters and pagination.
         */
        advancedSearchClear: function() {
            this.filters = {
                last_name:          '',
                name:               '',
                document_number:    '',
                cuil:               '',
                risk:               '',
                sex:                '',
                group_id:           [],
                company_id:         [],
                activity_id:        [],
            };
            this.paginate(1);
        }
    }
}
</script>