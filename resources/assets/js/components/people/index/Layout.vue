<style scoped>
    .table-wrapper {
        min-height: 60vh;
        position:relative;
        overflow-x: auto;
    }
    table.table-hover > thead > tr > th {
        border-top: 0px;
        cursor: pointer;
        width: 25%; 
    }
    table.table-hover > tbody > tr:hover {
        cursor: pointer;
        background-color: #f1f7fc;
    }
    div.searcher {
        background: #fafafa;
    }
    input {
        margin-bottom: 5px;
    }
</style>

<template>
    <div>
        <div  v-if="!this.$store.state.people.updating" class="card searcher">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <input placeholder="Apellido" dusk="last_name" name="last_name" v-model="last_name" type="text" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input placeholder="Nombre" dusk="name" name="name" v-model="name" type="text" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input placeholder="CUIL" dusk="cuil" name="cuil" v-model="cuil" type="text" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input placeholder="Nombre de la empresa" dusk="company_name" name="company" v-model="company_name" type="text" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body table-wrapper">
                <loading-cover v-if="this.$store.state.people.updating" message="Cargando..."/>
                <template v-else>
                    <custom-table
                        :columns="[ 
                            {name: 'last_name', text: 'Apellido'},
                            {name: 'name', text: 'Nombre'},
                            {name: 'cuil', text: 'CUIL / CUIT'},
                            {name: 'company_name', text: 'Empresa'},
                        ]"
                        :rows="people"
                        :filter="{
                            strict: true,
                            conditions: {
                                last_name: this.last_name,
                                name: this.name,
                                cuil: this.cuil,
                                company_name: this.company_name 
                            }
                        }"
                        @rowclicked="showProfile"
                    />
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                last_name: "",
                name: "",
                cuil: "",
                company_name: "",
                people: [],
                currentSortedColumn: "company_name",
                currentSortingOrder: 1
            }
        },
        beforeMount() {
            this.$store.dispatch('fetch', 'people');
            this.people = this.unfilteredPeople;
        },
        computed: {
            unfilteredPeople: function() { return this.$store.state.people.list }
        },
        watch: {
            unfilteredPeople: function() {
                this.people = this.unfilteredPeople;
            }
        },
        methods: {
            showProfile: function(person) {
                this.$router.push(`/people/show/${person.id}`);
            },
        }
    }
</script>
