<template>
    <index-wrapper  :conditions="conditions" :updating="updating" advanced-search-route="/people/id-search"
                    @advanced-search-success="advancedSearchSuccess"
                    @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col col-xl-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Documento" v-model="conditions.document_number">
                </div>
                <div class="col col-xl-2">
                    <input type="text" class="form-control form-control-sm" placeholder="CUIL / CUIT" v-model="conditions.cuil">
                </div>
                <div class="col col-xl-3">
                    <select2    :value="conditions.risk" @input="value => conditions.risk"
                                placeholder="Nivel de riesgo" :options="risks" size="small"/>
                </div>
                <div class="col col-xl-5">
                    <select2    :value="conditions.group_id" @input="value => conditions.group_id = value"
                                placeholder="Grupos" :options="groups" size="small" multiple/>
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="people" @rowclicked="showProfile"/>
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                {name: 'last_name',    text: 'Apellido',    width:'20'},
                {name: 'name',         text: 'Nombre',      width:'20'},
                {name: 'cuil',         text: 'CUIL / CUIT', width:'20'},
                {name: 'company_name', text: 'Empresa',     width:'40'}
            ],
            conditions: {
                document_number:    '',
                cuil:               '',
                risk:               '',
                group_id:           [],
            },
            filtered_ids: null,
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'groups');
        this.$store.dispatch('fetchList', 'people');
    },
    computed: {
        // 
        updating: function() {
            return  this.$store.getters.people.updating || this.$store.getters.groups.updating;
        },
        // List of people.
        people: function() {
            let all = this.$store.getters.people.list;
            return this.filtered_ids ? all.filter(person => this.filtered_ids.includes(person.id)) : all;
        },
        // List of groups formated to be used as options.
        groups: function() {
            return this.$store.getters.groups.asOptions();
        },
        // List of risks formated to be used as options.
        risks: function() {
            return this.$store.getters.static_lists.risks.asOptions();
        }
    },
    methods: {
        // Redirects to the profile of the clicked person.
        showProfile: function(person) {
            this.$router.push(`/people/show/${person.id}`);
        },
        advancedSearchSuccess: function(ids) {
            this.filtered_ids = ids;
        },
        advancedSearchClear: function() {
            this.filtered_ids = null;
            this.conditions = {
                document_number:    '',
                cuil:               '',
                risk:               '',
                group_id:           [],
            };
        }
    }
}
</script>