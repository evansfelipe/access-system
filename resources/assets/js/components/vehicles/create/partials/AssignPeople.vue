<style lang="scss" scoped>    
    .dropdown-toggle::after {
        display:none;
    }

    .btn-link {
        color: grey;
        &:hover {
            text-decoration: none;
        }
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.people.updating" message="Cargando..."/>
        <template v-else>
            <!-- Options buttons -->
            <div class="row text-center mb-3">
                <!-- Company people list button -->
                <div class="col-4">
                    <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'company' ? 'outline-unique btn-static':'link')"
                            @click="selected_list = 'company'"
                            :disabled="company_people.length <= 0">
                        <abbreviation-text :text="companyname"/> <span class="badge badge-dark ml-1">{{ company_people.length }}</span>
                    </button>
                </div>
                <!-- Other people list button -->
                <div class="col-4">
                    <button type="button" :class="'font-weight-bold btn btn-block btn-' + (selected_list === 'others' ? 'outline-unique btn-static':'link')"
                            @click="selected_list = 'others'"
                            :disabled="other_people.length <= 0">
                        Otras empresas <span class="badge badge-dark ml-1">{{ other_people.length }}</span>
                    </button>
                </div>
                <!-- Search input -->
                <div class="col-3">
                    <input type="text" class="form-control" v-model="filter.conditions.all" placeholder="Búsqueda">
                </div>
                <!-- Extra options dropdown -->
                <div class="col-1 pl-0">
                    <div class="dropdown show">
                        <!-- Open/Close button -->
                        <a class="btn btn-link btn-block dropdown-toggle" role="button" id="more-options" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        title="Más opciones">
                            <i class="fas fa-bars fa-lg"></i>
                        </a>
                        <!-- Options -->
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="more-options">
                            <!-- New person -->
                            <span class="dropdown-item">
                                <i class="fas centered fa-plus"></i> Agregar nueva persona
                            </span>
                            <!-- Unselect all people -->
                            <span class="dropdown-item" @click="unpickAll">
                                <i class="fas centered fa-ban"></i> Deseleccionar todos
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /Extra options dropdown -->
            </div>
            <!-- /Options buttons -->
            <!-- People list -->
            <div class="row">
                <div class="col-12 table-container">
                    <custom-table
                        :columns="columns"
                        :rows="people_list"
                        :filter="filter"
                        :pickable="{
                            active: true,
                            list: people_picked
                        }"
                        @rowclicked="togglePerson"
                    />
                </div>
            </div>
            <!-- /People list -->
        </template>
    </div>
</template>

<script>
export default {
    props: {
        companyname: { 
            required: false,
            type: String,
            default: '-'
        }
    },
    data: function() {
        return {
            people_list: [],
            others_people: [],
            company_people: [],
            selected_list: "",
            show_outdated: false,
            columns: [ 
                {name: 'last_name', text: 'Apellido'},
                {name: 'name', text: 'Nombre'},
                {name: 'cuil', text: 'CUIL / CUIT'}
            ],
            filter: {
                strict: false,
                conditions: { all: "" }
            }
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'people');
        this.splitLists(this.companyname);
    },
    computed: { 
        people: function() {
            return this.$store.getters.people.list;
        },
        people_picked: function() {
            return this.$store.getters.vehicle.values.assign_people.people_id;
        }
    }, 
    methods: {
        splitLists(company_name) {
            this.other_people  = this.people.filter(person => person.company_name !== company_name);
            this.company_people = this.people.filter(person => person.company_name === company_name);
            if(this.people.length > 0) {
                this.selected_list = this.company_people.length > 0 ? 'company' : 'others';
            }
        },
        unpickAll() {
            this.$store.getters.people.list.forEach(person => this.$store.commit('pickPerson', { id: person.id, value: false }));
        },
        togglePerson(person) {
            this.$store.commit('pickPerson', person.id);
        }
    },
    watch: {
        people: function() {
            this.splitLists(this.companyname);
        },
        companyname: function(value) {
            this.splitLists(value);
        },
        selected_list: function(value) {
            this.people_list = value === 'company' ? this.company_people : this.other_people;
        }
    }
}
</script>
