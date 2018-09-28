<style lang="scss" scoped>    
    .dropdown-toggle::after {
        display:none;
    }

    .btn-list, .btn-list:disabled {
        background-color: transparent;
        font-weight: bold;
        border: 1px solid grey;
        color: grey;
    }

    .btn-list.selected {
        border-color: #3F729B;
        color: #3F729B;
    }
</style>

<template>
    <div>
        <loading-cover v-if="this.$store.getters.people.updating"/>
        <template v-else>
            <!-- Options buttons -->
            <div class="row text-center mb-3">
                <div class="col-12">
                    <div class="row">
                        <!-- Company people list button -->
                        <div class="col-4">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'company' ? 'selected' : ''}`"
                                    @click="selected_list = 'company'"
                                    :disabled="company_people.length <= 0">
                                <span class="badge badge-dark ml-1">{{ company_people.length }}</span> <abbreviation-text :text="companyname"/>
                            </button>
                        </div>
                        <!-- other people list button -->
                        <div class="col-4">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'other' ? 'selected' : ''}`"
                                    @click="selected_list = 'other'"
                                    :disabled="other_people.length <= 0">
                                <span class="badge badge-dark ml-1">{{ other_people.length }}</span> Otras empresas
                            </button>
                        </div>
                        <!-- Selected people list button -->
                        <div class="col-4">
                            <button type="button" :class="`btn btn-block btn-list ${selected_list === 'picked' ? 'selected' : ''}`"
                                    @click="selected_list = 'picked'"
                                    :disabled="people_picked.length <= 0">
                                <span class="badge badge-dark ml-1">{{ people_picked.length }}</span> Seleccionados
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Options buttons -->
            <!-- People list -->
            <div class="row">
                <div class="col-12 table-container">
                    <custom-table
                        :columns="columns"
                        :rows="people_list"
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
        companyid: { 
            required: false,
            type: Number,
            default: null
        },
        companyname: { 
            required: false,
            type: String,
            default: 'Empresas asignadas'
        }
    },
    data: function() {
        return {
            people_list: [],
            other_people: [],
            company_people: [],
            selected_list: '',
            show_outdated: false,
            columns: [ 
                {name: 'last_name', text: 'Apellido'},
                {name: 'name',      text: 'Nombre'},
                {name: 'cuil',      text: 'CUIL / CUIT'}
            ]
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'people');
        this.splitList();
    },
    computed: {
        people: function() {
            return this.$store.getters.people.list;
        },
        people_picked: function() {
            return this.$store.getters.vehicle.values.assign_people.people_id;
        },
        picked_list: function() {
            let list = [];
            this.people_picked.forEach(person_id => {
                list.push(this.$store.getters.people.getById(person_id));
            });
            return list;
        }
    }, 
    methods: {
        splitList() {
            this.company_people = [];
            this.other_people = [];
            this.people.forEach(person => {
                if( person.companies.includes(this.companyid) ) {
                    this.company_people.push(person);
                }
                else {
                    this.other_people.push(person);
                }
            });
            this.selected_list = this.company_people.length > 0 ? 'company' : 'other';
            this.people_list = this.company_people.length > 0 ? this.company_people : this.other_people;
        },
        togglePerson(person) {
            this.$store.commit('pickPerson', person.id);
        }
    },
    watch: {
        companyid: function() {
            this.splitList();
        },
        people: function() {
            this.splitList();
        },
        selected_list: function(value) {
            if( value === 'company' ) {
                this.people_list = this.company_people;
            }
            else if( value === 'other' ) {
                this.people_list = this.other_people;
            }
            else if( value === 'picked' ) {
                this.people_list = this.picked_list;
            }
        }
    }
}
</script>
