<template>
    <index-wrapper :updating="updating" @advanced-search="advancedSearch">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Apellido" v-model="filter.conditions.last_name">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Nombre" v-model="filter.conditions.name">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="CUIL" v-model="filter.conditions.cuil">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" class="form-control form-control-sm" placeholder="Nombre de la empresa" v-model="filter.conditions.company_name">
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <custom-table :columns="columns" :rows="people" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                { name: 'last_name',    text: 'Apellido',    width:'20'},
                { name: 'name',         text: 'Nombre',      width:'20'},
                { name: 'cuil',         text: 'CUIL / CUIT', width:'20'},
                { name: 'company_name', text: 'Empresa',     width:'40'}
            ],
            filter: {
                strict: true,
                conditions: {
                    last_name:    "",
                    name:         "",
                    cuil:         "",
                    company_name: ""
                }
            },
            people: [],
            advanced_search: false
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'people');
        this.people = this.$store.getters.people.list;
    },
    computed: {
        /**
         * Returns whether the list of people is being updated or not.
         */
        updating: function() { return this.$store.getters.people.updating },
        /**
         * Returns the list of people from the store.
         */
        unfilteredPeople: function() { return this.$store.getters.people.list }
    },
    watch: {
        /**
         * When the unfiltered list of people changes, resets the people list and the filters.
         */
        unfilteredPeople: function() { 
            this.people = this.unfilteredPeople;
            this.filter = {
                strict: true,
                conditions: { last_name: "", name: "", cuil: "", company_name: "" }
            }
        },
    },
    methods: {
        /**
         * Redirects to the profile of the clicked person.
         */
        showProfile: function(person) {
            this.$router.push(`/people/show/${person.id}`);
        },
        /**
         * Restarts filters and show/hide advanced search.
         */
        advancedSearch: function() {
            // this.advanced_search = !this.advanced_search;
            if( this.advanced_search === true ) {
                this.filter.conditions = {
                    plate:        "",
                    brand:        "",
                    model:        "",
                    year:         "",
                    company_name: ""
                };
            }
        }
    }
}
</script>