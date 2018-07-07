<style scoped>
    table.table-hover > thead > tr > th {
        border-top: 0px;
        cursor: pointer;
        width: 25%;
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Firefox */
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
</style>

<template>
    <div>
        <div class="card searcher">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <input placeholder="Apellido" name="last_name" v-model="lastName" type="text" class="form-control" v-on:keyup="sendRequest">
                    </div>
                    <div class="col-12 col-md-3">
                        <input placeholder="Nombre" name="name" v-model="name" type="text" class="form-control" v-on:keyup="sendRequest">
                    </div>
                    <div class="col-12 col-md-3">
                        <input placeholder="CUIL" name="cuil" v-model="cuil" type="text" class="form-control" v-on:keyup="sendRequest">
                    </div>
                    <div class="col-12 col-md-3">
                        <input placeholder="Nombre de la empresa" name="company" v-model="companyName" type="text" class="form-control" v-on:keyup="sendRequest">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" v-if="people.length > 0">
                    <thead>
                        <tr>
                            <th id="last_name" @click="orderBy('last_name')">Apellido</th>
                            <th id="name" @click="orderBy('name')">Nombre</th>
                            <th id="cuil" @click="orderBy('cuil')">Cuil</th>
                            <th id="company_name" @click="orderBy('company_name')">Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(person, key) in people" @click="showProfile(person.url)" v-bind:key="key">
                            <td>{{ person.last_name }}</td>
                            <td>{{ person.name }}</td>
                            <td>{{ person.cuil }}</td>
                            <td>{{ person.company.name }}</td>
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
        mounted() {
            axios.get('/people').then(response => {
                this.$data.people = response.data;
                this.orderBy(this.$data.currentOrderBy);
            });
        },
        data: function() {
            return {
                name: "",
                lastName: "",
                cuil: "",
                companyName: "",
                people: [{
                    name: "", 
                    last_name: "",
                    cuil: "",
                    company: {
                        name:""
                    }
                }],
                currentOrderBy: "company_name",
                currentOrder: -1
            }
        },
        methods: {
            sendRequest: function() {
                let query = 'namefilter=' + this.$data.name;
                query += '&lastnamefilter=' + this.$data.lastName;
                query += '&cuilfilter=' + this.$data.cuil;
                query += '&companynamefilter=' + this.$data.companyName;
                axios.get('/people?' + query).then(response => {
                    this.$data.people = response.data;
                    this.$data.currentOrder *= -1;
                    this.orderBy(this.$data.currentOrderBy);
                });
            },
            showProfile: function(url) {
                window.location.href = url;
            },
            orderBy: function(col) {
                if(this.$data.people.length > 0) {
                    if(col === this.$data.currentOrderBy) {
                        this.$data.currentOrder *= -1;
                    }
                    else {
                        this.$data.currentOrder = 1;
                    }
                    this.$data.people.sort((a, b) => {
                        if(col === 'company_name') {
                            return this.$data.currentOrder * a.company.name.localeCompare(b.company.name);
                        }
                        return this.$data.currentOrder * a[col].localeCompare(b[col]);
                    });


                    let column = document.getElementById(this.$data.currentOrderBy);
                    let actualIcon = column.getElementsByTagName('I')[0];

                    if(actualIcon) {
                        column.removeChild(actualIcon);
                    }

                    this.$data.currentOrderBy = col;
                    column = document.getElementById(col);
                    
                    let icon = document.createElement("I");
                    if(this.$data.currentOrder > 0) {
                        icon.classList += "fas fa-sort-up float-right";
                    }
                    else {
                        icon.classList += "fas fa-sort-down float-right";
                    }

                    column.appendChild(icon);
                }
            }
        }
    }
</script>
