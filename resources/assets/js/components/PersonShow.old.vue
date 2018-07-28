<style scoped>
    .nav-tabs > .nav-item {
        cursor: pointer;
    }

    .nav-tabs > .nav-item > a.active {
        background-color: white;
        border-bottom-color: white;
        cursor: auto;
        color: black  !important;
    }

    a.inactive {
        color: grey !important;
    }

    .nav-item + .nav-item {
        margin-left: 1px;
    }

    .card {
        border-top: 0;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }

    table {
        width: 100%;
    }

    .strong {
        font-weight: bold;
    }
</style>

<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 0 ? 'active' : 'inactive')" @click="changeTab(0)">
                    <i class="fas fa-user"></i>
                    Información personal
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 1 ? 'active' : 'inactive')" @click="changeTab(1)">
                    <i class="fas fa-briefcase"></i>
                    Información laboral
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 2 ? 'active' : 'inactive')" @click="changeTab(2)">
                    <i class="fas fa-car"></i> 
                    Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 3 ? 'active' : 'inactive')" @click="changeTab(3)">
                    <i class="fas fa-id-card"></i>
                    Tarjeta
                </a>
            </li>
            <li class="nav-item">
                <a :class="'nav-link ' + (tab_number === 4 ? 'active' : 'inactive')" @click="changeTab(4)">
                    <i class="fas fa-file-alt"></i>
                    Documentación
                </a>

            </li>
        </ul>
        <!-- Content -->
        <div class="card card-default">
            <div class="card-body">
                <!-- Content for the tab number 0 -->
                <div v-if="tab_number === 0" class="row d-flex align-items-center">
                    <div class="col-md-4 text-center">
                        <img class="img-fluid rounded-circle" src="/pictures/no-image.jpg" alt="Subir imagen">
                    </div>
                    <div class="col-md-8">
                        <h3>{{ person.full_name }}</h3>
                        <table>
                            <!-- Document -->
                            <tr>
                                <td style="width: 40%">{{ person.document_type }}</td>
                                <td class="strong">{{ person.document_number }}</td>
                            </tr>
                            <!-- Cuil/Cuit -->
                            <tr>
                                <td>CUIL / CUIT</td>
                                <td class="strong">{{ person.cuil }}</td>
                            </tr>
                            <!-- Birthday -->
                            <tr>
                                <td>Fecha de nacimiento</td>
                                <td class="strong">{{ person.birthday }}</td>
                            </tr>
                            <!-- Sex -->
                            <tr>
                                <td>Género</td>
                                <td class="strong">{{ person.sex }}</td>
                            </tr>
                            <!-- Blood type -->
                            <tr>
                                <td>Grupo sanguíneo</td>
                                <td class="strong">{{ person.blood_type }}</td>
                            </tr>
                            <!-- PNA -->
                            <tr>
                                <td>Prontuario PNA</td>
                                <td class="strong">{{ person.pna }}</td>
                            </tr>
                            <!-- Mail -->
                            <tr>
                                <td>Mail</td>
                                <td class="strong">{{ person.email }}</td>
                            </tr>
                            <!-- Phone -->
                            <tr>
                                <td>Teléfono fijo</td>
                                <td class="strong">{{ person.phone }}</td>
                            </tr>
                            <!-- Mobile phone -->
                            <tr>
                                <td>Teléfono móvil</td>
                                <td class="strong">{{ person.mobile_phone }}</td>
                            </tr>
                            <!-- Fax -->
                            <tr>
                                <td>FAX</td>
                                <td class="strong">{{ person.fax }}</td>
                            </tr>
                            <!-- Residency -->
                            <tr>
                                <td>Domicilio</td>
                                <td class="strong">{{ person.residency }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- Content for the tab number 1 -->
                <div v-if="tab_number === 1">
                    <div class="row">
                        <div class="offset-1 col-5">
                            <h4>{{ company.name }}</h4>
                            <table>
                                <tr>
                                    <td>CUIT</td>
                                    <td class="strong">{{ company.cuit }}</td>
                                </tr>
                                <tr>
                                    <td>Sector</td>
                                    <td class="strong">{{ company.area }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-5">
                            ART
                        </div>
                    </div>
                </div>
                <!-- Content for the tab number 2 -->
                <div v-if="tab_number === 2">
                    Vehículos
                </div>
                <!-- Content for the tab number 3 -->
                <div v-if="tab_number === 3">
                    Tarjetas
                </div>
                <!-- Content for the tab number 4 -->
                <div v-if="tab_number === 4">
                    Documentación
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            personjson: {
                required: true
            },
            companyjson: {
                required: true
            }
        },
        data: function() {
            return {
                tab_number: 0,
                person: JSON.parse(this.personjson),
                company: JSON.parse(this.companyjson)
            };
        },
        methods: {
            changeTab: function(tab_number) {
                this.tab_number = tab_number;
            }
        }
    }
</script>
