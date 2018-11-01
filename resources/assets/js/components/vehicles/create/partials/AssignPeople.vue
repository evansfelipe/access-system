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
                <button :disabled="!companyId" type="button" :class="`btn btn-list ${selected_list === 'company' ? 'selected' : ''}`" @click="getCompanyPeople">
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

        <remote-custom-table    list="people" 
                                :columns="columns"
                                :filters="filters"
                                :pickable="{ active: true, list: people_picked }"
                                @rowclicked="togglePerson"
        />
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
            columns: [ 
                { name: 'last_name',            text: 'Apellido'    },
                { name: 'name',                 text: 'Nombre'      },
                { name: 'document_number',      text: 'Documento'   },
                { name: 'cuil',                 text: 'CUIL / CUIT' }
            ],
            filters: {
                not_company_id: [],
                company_id:     [],
                id:             []
            },
        };
    },
    mounted() {
        this.getOtherPeople();
    },
    computed: {
        /**
         * An array with the ids of the assigned people for the vehicle.
         */
        people_picked: function() {
            return this.$store.getters.vehicle.values.assign_people.people_id;
        },
    }, 
    methods: {
        /**
         * 
         */
        resetFilters: function() {
            this.filters = {
                not_company_id: [],
                company_id:     [],
                id:             []
            };
        },
        /**
         * 
         */
        getCompanyPeople: function() {
            this.selected_list = 'company';
            this.resetFilters();
            this.filters.company_id = [this.companyId];
        },
        /**
         * 
         */
        getOtherPeople: function() {
            this.selected_list = 'other';
            this.resetFilters();
            this.filters.not_company_id = this.companyId ? [this.companyId] : [];
        },
        /**
         * 
         */
        getPickedPeople: function() {
            this.selected_list = 'picked';
            this.resetFilters();
            this.filters.id = this.people_picked;
        },
        /**
         * 
         */
        togglePerson(person) {
            this.$store.commit('pickPerson', person.id);
        }
    },
    watch: {
        companyId: {
            handler: function(newValue) {
                switch(this.selected_list) {
                    case 'company':
                        if(newValue)
                            this.getCompanyPeople();
                        else
                            this.getOtherPeople();
                        break;
                    case 'other':
                        this.getOtherPeople();
                        break;
                    case 'picked':
                        this.getPickedPeople();
                        break;
                }
            },
            deep: true
        }
    }
}
</script>
