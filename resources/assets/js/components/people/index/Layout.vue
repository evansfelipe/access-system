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
                <div class="col col-xl-4">
                    <input class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Apellido" v-model="tempFilters.last_name"/>
                </div>
                <div class="col col-xl-4">
                    <input class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Nombre" v-model="tempFilters.name"/>
                </div>
                <div class="col col-xl-4">
                    <input class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Documento" v-model="tempFilters.document_number"/>
                </div>
            </div>
            <div class="form-row">
                <div class="col col-xl-4">
                    <input class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="CUIL / CUIT" v-model="tempFilters.cuil"/>
                </div>
                <div class="col col-xl-4">
                    <select2    :value="tempFilters.risk" @input="value => tempFilters.risk = value"
                                placeholder="Nivel de riesgo" :options="risks" size="small"/>
                </div>
                <div class="col col-xl-4">
                    <select2    :value="tempFilters.sex" @input="value => tempFilters.sex = value"
                                placeholder="Género" :options="sexes" size="small"/>
                </div>

            </div>
            <div class="form-row">
                <div class="col col-xl-6">
                    <remote-select2 size="small" :value="tempFilters.company_id" path="/selects/companies" multiple
                                    @input="value => tempFilters.company_id = value" placeholder="Empresa/s"/>
                </div>
                <div class="col col-xl-6">
                    <remote-select2 size="small" :value="tempFilters.activity_id" path="/selects/activities" multiple
                                    @input="value => tempFilters.activity_id = value" placeholder="Actividad/es"/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <remote-custom-table    list="people" 
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
                { name: 'last_name',         text: 'Apellido',      width:'20' },
                { name: 'name',              text: 'Nombre',        width:'20' },
                { name: 'document_number',   text: 'Documento',     width:'15' },
                { name: 'cuil',              text: 'CUIL / CUIT',   width:'15' },
                { name: 'company_name',      text: 'Empresa',       width:'30' }
            ],
            filters: {},
            tempFilters: {
                last_name:          '',
                name:               '',
                document_number:    '',
                cuil:               '',
                risk:               '',
                sex:                '',
                group_id:           [],
                company_id:         [],
                activity_id:        [],
            }
        }
    },
    computed: {
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
         * 
         */
        showProfile: function(person) {
            this.$router.push(`/people/show/${person.id}`);
        },
        /**
         * Restarts filters.
         */
        advancedSearchClear: function() {
            this.tempFilters = {
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
            this.commitFilters();
        },
        /**
         * 
         */
        commitFilters: function() {
            this.filters = {
                last_name:          this.tempFilters.last_name,
                name:               this.tempFilters.name,
                document_number:    this.tempFilters.document_number,
                cuil:               this.tempFilters.cuil,
                risk:               this.tempFilters.risk,
                sex:                this.tempFilters.sex,
                group_id:           this.tempFilters.group_id,
                company_id:         this.tempFilters.company_id,
                activity_id:        this.tempFilters.activity_id,
            };
        }
    }
}
</script>