<style scoped>
    .table-wrapper {
        overflow-x: auto;
    }
    table.table-hover > thead > tr > th {
        border-top: 0px;
        cursor: pointer;
        width: 25%;
        -webkit-touch-callout: none; /* iOS Safari */
        -moz-user-select: none; /* Firefox */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
            -ms-user-select: none; /* Internet Explorer/Edge */
                user-select: none; /* Non-prefixed version, currently supported by Chrome and Opera */
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
        <div class="card searcher">
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
                <table class="table table-hover" v-if="people.length > 0">
                    <thead>
                        <tr>
                            <th id="last_name" @click="sortColumn('last_name')">Apellido</th>
                            <th id="name" @click="sortColumn('name')">Nombre</th>
                            <th id="cuil" @click="sortColumn('cuil')">Cuil</th>
                            <th id="company_name" @click="sortColumn('company_name')">Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(person, key) in people" @click="showProfile(person.url)" v-bind:key="key">
                            <td dusk="td_last_name">{{ person.last_name }}</td>
                            <td>{{ person.name }}</td>
                            <td>{{ person.cuil }}</td>
                            <td>{{ person.company_name }}</td>
                        </tr>
                    </tbody>
                </table>
                <h3 class="text-center" v-else>No se encontraron coincidencias</h3>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: { initialpeople: { type: String, required: true } },
        data: function() {
            let parsedPeople = JSON.parse(this.initialpeople);
            return {
                last_name: "",
                name: "",
                cuil: "",
                company_name: "",
                unfilteredPeople: parsedPeople,
                people: parsedPeople,
                currentSortedColumn: "company_name",
                currentSortingOrder: 1
            }
        },
        watch: {
            last_name: function() {
                this.people = this.unfilteredPeople.filter(person => this.applyFiltersConditions(person));
            },
            name: function() {
                this.people = this.unfilteredPeople.filter(person => this.applyFiltersConditions(person));
            },
            cuil: function() {
                this.people = this.unfilteredPeople.filter(person => this.applyFiltersConditions(person));
            },
            company_name: function() {
                this.people = this.unfilteredPeople.filter(person => this.applyFiltersConditions(person));
            }
        },
        methods: {
            applyFiltersConditions(person) {
                return person.last_name.toUpperCase().includes(this.last_name.toUpperCase()) && person.name.toUpperCase().includes(this.name.toUpperCase())
                       && person.cuil.toUpperCase().includes(this.cuil.toUpperCase()) && person.company_name.toUpperCase().includes(this.company_name.toUpperCase());
            },
            showProfile: function(url) {
                window.location.href = url;
            },
            sortColumn: function(colID) {
                if(this.people.length > 0) {
                    // If the column that is being sorted hasn't changed since the last time, then reverse the sorting order.
                    // Otherwise, resets the sorting order to the default.
                    this.currentSortingOrder = colID === this.currentSortedColumn ? -this.currentSortingOrder : 1;
                    // Sorts the people array using the target column and the sorting order.
                    this.people.sort((a, b) => this.currentSortingOrder * a[colID].localeCompare(b[colID]));
                    // Tries to get the fontawesome icon from the target column.
                    let targetColumn = document.getElementById(this.currentSortedColumn);
                    let icon = targetColumn.getElementsByTagName('i')[0];
                    // If the icon exists, then removes the icon.
                    if(icon) targetColumn.removeChild(icon);
                    // Updates the current sorted column and gets the reference to this document element.
                    this.currentSortedColumn = colID;
                    targetColumn = document.getElementById(colID);
                    // Creates a new i element that will be used to place the new fontawesome icon.
                    icon = document.createElement("i");
                    // Add the correspondent class to the icon based on the current sorting order.
                    icon.classList.add('float-right', 'fas', this.currentSortingOrder > 0 ? 'fa-sort-up' : 'fa-sort-down');
                    // Appends the icon as a child of the current sorted column.
                    targetColumn.appendChild(icon);
                }
            }
        }
    }
</script>
