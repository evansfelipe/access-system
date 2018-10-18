<template>
    <index-wrapper :updating="updating" @advanced-search="advancedSearch">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="filter.conditions.name">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Empresa" v-model="filter.conditions.company">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Entrada" v-model="filter.conditions.gate">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Franja horaria" v-model="filter.conditions.range">
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="groups" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                { name: 'name',         text: 'Nombre',         width:'40'},
                { name: 'company',      text: 'Empresa',        width:'20'},
                { name: 'zone',         text: 'Zona',        width:'20'},
                { name: 'range',        text: 'Franja horaria', width:'20'}
            ],
            filter: {
                strict: true,
                conditions: {
                    name:       "",
                    company:    "",
                    gate:       "",
                    range:      ""
                }
            },
            groups: [],
            advanced_search: false
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'groups');
        this.groups = this.$store.getters.groups.list;
    },
    computed: {
        /**
         * Returns whether the list of groups is being updated or not.
         */
        updating: function() { return this.$store.getters.groups.updating },
        /**
         * Returns the list of groups from the store.
         */
        unfilteredGroups: function() { return this.$store.getters.groups.list }
    },
    watch: {
        /**
         * When the unfiltered list of groups changes, resets the groups list and the filters.
         */
        unfilteredGroups: function() { 
            this.groups = this.unfilteredGroups;
            this.filter = {
                strict: true,
                conditions: {
                    name:       "",
                    company:    "",
                    gate:       "",
                    range:      ""
                }
            }
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked group.
         */
        showProfile: function(group) {
            this.$router.push(`/groups/show/${group.id}`);
        },
        /**
         * Restarts filters and show/hide advanced search.
         */
        advancedSearch: function() {
            // this.advanced_search = !this.advanced_search;
            if( this.advanced_search === true ) {
                this.filter.conditions = {
                    name:       "",
                    company:    "",
                    gate:       "",
                    range:      ""
                };
            }
        }
    }
}
</script>