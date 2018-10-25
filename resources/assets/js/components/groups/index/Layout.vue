<template>
    <index-wrapper @advanced-search-submit="paginate(1)" @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="filters.name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Empresa" v-model="filters.company">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Zona" v-model="filters.zone">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Franja horaria" v-model="filters.range">
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :updating="updating" :columns="columns" :rows="groups" @rowclicked="showProfile"/>
            <br v-if="groups.length > 0">
            <paginator-links v-if="groups.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                { name: 'name',         text: 'Nombre',         width:'40' },
                { name: 'company',      text: 'Empresa',        width:'20' },
                { name: 'zone',         text: 'Zona',           width:'20' },
                { name: 'range',        text: 'Franja horaria', width:'20' }
            ],
            filters: {
                name:       "",
                company:    "",
                zone:       "",
                range:      ""
            },
        }
    },
    beforeMount() {
        this.paginate(1);
    },
    computed: {
        /**
         * Returns whether the list of groups is being updated or not.
         */
        updating: function() { return this.$store.getters.groups.updating },
        /**
         * Returns the groups of the current page.
         */
        groups: function() {
            return this.$store.getters.groups.paginator.data;
        },
        /**
         * Returns the current and the last page of the pagination.
         */
        paginator: function() {
            return {
                current_page: this.$store.getters.groups.paginator.current_page,
                last_page: this.$store.getters.groups.paginator.last_page
            };
        }
    },
    methods: {
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'groups', page: page, filters: this.filters});
        },
        /**
         * Redirects to the profile of the clicked group.
         */
        showProfile: function(group) {
            this.$router.push(`/groups/show/${group.id}`);
        },
        /**
         * Restarts filters and asks to the server for the first page.
         */
        advancedSearchClear: function() {
            this.filters = {
                name:       "",
                company:    "",
                zone:       "",
                range:      ""
            };
            this.paginate(1);
        }
    }
}
</script>