<template>
    <div>
        <div class="card">
            <div class="card-body" style="background: #fafafa">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <input v-model="filter.conditions.last_name" type="text" class="form-control" :disabled="updating" placeholder="Apellido">
                    </div>
                    <div class="col-12 col-md-3">
                        <input v-model="filter.conditions.name" type="text" class="form-control" :disabled="updating" placeholder="Nombre">
                    </div>
                    <div class="col-12 col-md-3">
                        <input v-model="filter.conditions.cuil" type="text" class="form-control" :disabled="updating" placeholder="CUIL">
                    </div>
                    <div class="col-12 col-md-3">
                        <input v-model="filter.conditions.company_name" type="text" class="form-control" :disabled="updating" placeholder="Nombre de la empresa">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" :style="updating ? 'min-height: 60vh' : ''">
                <loading-cover v-if="updating" message="Cargando..."/>
                <custom-table  v-else :columns="columns" :rows="people" :filter="filter" @rowclicked="showProfile"/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                columns: [
                    { name: 'last_name',    text: 'Apellido'    },
                    { name: 'name',         text: 'Nombre'      },
                    { name: 'cuil',         text: 'CUIL / CUIT' },
                    { name: 'company_name', text: 'Empresa'     }
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
                people: []
            }
        },
        beforeMount() {
            this.$store.dispatch('fetch', 'people');
            this.people = this.$store.state.people.list;
        },
        computed: {
            /**
             * Returns whether the list of people is being updated or not.
             */
            updating: function() { return this.$store.state.people.updating },
            /**
             * Returns the list of people from the store.
             */
            unfilteredPeople: function() { return this.$store.state.people.list }
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
        }
    }
</script>