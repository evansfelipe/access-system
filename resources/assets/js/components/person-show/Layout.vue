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

    .btn-edit {
        position: absolute;
        top: 0.5em;
        right: 0.5em; 
        z-index: 2;
        text-align: center;
        background-color: transparent;
        color: rgb(168, 168, 168);
        border-radius: 100%;
        height: 3.5em;
        width: 3.5em;
    }

    .btn-edit:hover {
        background-color: whitesmoke;
        color: grey;
        
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
                <!-- Edit button -->
                <button class="btn btn-edit" title="Editar" @click="editProfile(edit_route)"><i class="fas fa-user-edit fa-lg"></i></button>
                <!-- Content for the tab number 0 -->
                <ps-personal-information v-if="tab_number === 0" :person="personal_information"/>
                <!-- Content for the tab number 1 -->
                <ps-working-information v-if="tab_number === 1" :personCompany="working_information"/>
                <!-- Content for the tab number 2 -->
                <ps-vehicles v-if="tab_number === 2"/>
                <!-- Content for the tab number 3 -->
                <ps-cards v-if="tab_number === 3"/>
                <!-- Content for the tab number 4 -->
                <ps-documentation v-if="tab_number === 4"/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            personjson: {
                required: true
            }
        },
        data: function() {
            let person_info = JSON.parse(this.personjson);
            return {
                tab_number: 0,
                edit_route: person_info.edit_url,
                personal_information: person_info.personal_information,
                working_information: person_info.working_information
            };
        },
        methods: {
            changeTab: function(tab_number) {
                this.tab_number = tab_number;
            },
            editProfile: function(url) {
                window.location.href = url;
            }
        }
    }
</script>
