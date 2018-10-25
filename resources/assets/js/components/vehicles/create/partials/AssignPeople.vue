<style lang="scss" scoped>
    .btn-list {
        width: 100%;
        background-color: transparent;
        border: 1px solid grey;

        &:disabled {
            color: grey;
        }

        &.selected {
            border-color: #3F729B;
            color: #3F729B;
            font-weight: bold;
        }
    }
</style>

<template>
    <div>
        <!-- Change list buttons -->
        <div class="form-row mb-3">
            <!-- Company people list button -->
            <div class="col-4">
                <button type="button" :class="`btn btn-list ${selected_list === 'company' ? 'selected' : ''}`" @click="getCompanyPeople">
                    Empresa seleccionada
                </button>
            </div>
            <!-- other people list button -->
            <div class="col-4">
                <button type="button" :class="`btn btn-list ${selected_list === 'other' ? 'selected' : ''}`" @click="getOtherPeople">
                    Otras empresas
                </button>
            </div>
            <!-- Selected people list button -->
            <div class="col-4">
                <button :disabled="people_picked.length <= 0" type="button" :class="`btn btn-list ${selected_list === 'picked' ? 'selected' : ''}`" @click="getPickedPeople">
                    <span class="badge badge-dark mr-1">{{ people_picked.length }}</span> Seleccionados
                </button>
            </div>
        </div>
        <!-- List -->
        <div class="row">
            <div class="col">
                <custom-table
                    :updating="updating"
                    :columns="columns" :rows="paginator.data"
                    :pickable="{ active: true, list: people_picked }"
                    @rowclicked="togglePerson"
                />
            </div>
        </div>
        <!-- Pagination and Search -->
        <div class="row mt-3">
            <div class="col-6">
                <search-input @input="searchHandler" placeholder="Filtrar columnas"/>
            </div>
            <div class="col-6">
                <paginator-links v-if="paginator.data.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        companyId: { 
            required: false,
            type: Number,
            default: null
        }
    },
    data: function() {
        return {
            selected_list: '',
            search: '',
            columns: [ 
                { name: 'last_name',            text: 'Apellido'    },
                { name: 'name',                 text: 'Nombre'      },
                { name: 'document_number',      text: 'Documento'   },
                { name: 'cuil',                 text: 'CUIL / CUIT' }
            ],
            filters: {
                wildcard:       '',
                not_company_id: [],
                company_id:     [],
                ids:            []
            }
        };
    },
    mounted() {
        this.getOtherPeople();
    },
    computed: {
        /**
         * 
         */
        updating: function() {
            return this.$store.getters.people.updating;
        },
        /**
         * An array with the ids of the assigned people for the vehicle.
         */
        people_picked: function() {
            return this.$store.getters.vehicle.values.assign_people.people_id;
        },
        /**
         * 
         */
        paginator: function() {
            return this.$store.getters.people.paginator;
        }
    }, 
    methods: {
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            this.$store.dispatch('paginateList', {what: 'people', page, filters: this.filters});
        },
        /**
         * 
         */
        searchHandler: function(value) {
            this.search = value;
            switch (this.selected_list) {
                case 'company':
                    this.getCompanyPeople();
                    break;
                case 'other':
                    this.getOtherPeople();
                    break;
                case 'picked':
                    this.getPickedPeople();
                    break;
            }
        },
        /**
         * 
         */
        resetFilters: function() {
            this.filters = {
                wildcard:       this.search,
                not_company_id: [],
                company_id:     [],
                ids:            []
            };
        },
        /**
         * 
         */
        getCompanyPeople: function() {
            this.selected_list = 'company';
            this.resetFilters();
            this.filters.company_id = [this.companyId];
            this.paginate(1);
        },
        /**
         * 
         */
        getOtherPeople: function() {
            this.selected_list = 'other';
            this.resetFilters();
            this.filters.not_company_id = [this.companyId];
            this.paginate(1);
        },
        /**
         * 
         */
        getPickedPeople: function() {
            this.selected_list = 'picked';
            this.resetFilters();
            this.filters.ids = this.people_picked;
            this.paginate(1);
        },
        /**
         * 
         */
        togglePerson(person) {
            this.$store.commit('pickPerson', person.id);
        }
    }
}
</script>
