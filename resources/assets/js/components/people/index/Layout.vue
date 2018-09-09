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
                            <input v-model="filter.conditions.last_name" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Apellido">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.name" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Nombre">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.cuil" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="CUIL">
                        </div>
                        <div class="col-12 col-md-3">
                            <input v-model="filter.conditions.company_name" type="text" class="form-control form-control-sm" :disabled="updating" placeholder="Nombre de la empresa">
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body" :style="updating ? 'min-height: 60vh' : ''">
                <loading-cover v-if="updating" message="Cargando..."/>
                <custom-table  v-else :columns="columns" :rows="people" :filter="filter" :advancedsearch="advanced_search" @rowclicked="showProfile"/>
            </div>
        </div>
    </div>
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
            this.advanced_search = !this.advanced_search;
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