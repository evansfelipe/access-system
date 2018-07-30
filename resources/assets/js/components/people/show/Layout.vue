<style lang="scss" scoped>
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

    .btn-edit {
        position: absolute;
        padding: 1em;
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
    
    div.card-body {
        min-height: 77vh;
        display: flex;
        align-items: center;
        & > div.row {
            width: 100%;
        }
    }
</style>

<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- Personal information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-user">
                Información personal
            </tab-item>
            <!-- Working information tab -->
            <tab-item :active="tab === 1" @click.native="tab = 1" icon="fas fa-briefcase">
                Información laboral
            </tab-item>
            <!-- Vehicles tab -->
            <tab-item :active="tab === 2" @click.native="tab = 2" icon="fas fa-car">
                Vehículos
            </tab-item>
            <!-- Card tab -->
            <tab-item :active="tab === 3" @click.native="tab = 3" icon="fas fa-id-card">
                Tarjeta
            </tab-item>
            <!-- Documentation tab -->
            <tab-item :active="tab === 4" @click.native="tab = 4" icon="fas fa-file-alt">
                Documentación
            </tab-item>
        </ul>
        <!-- Content -->
        <div class="card card-default">
            <div class="card-body">
                <!-- Edit button -->
                <a :href="edit_route" class="btn btn-edit"><i class="fas fa-user-edit fa-lg"></i></a>
                <!-- Content for the tab number 0 -->
                <ps-personal-information v-show="tab === 0" :person="personal_information"/>
                <!-- Content for the tab number 1 -->
                <ps-working-information v-show="tab === 1" :personCompany="working_information"/>
                <!-- Content for the tab number 2 -->
                <ps-vehicles v-show="tab === 2" :vehicles="vehicles"/>
                <!-- Content for the tab number 3 -->
                <ps-cards   v-show="tab === 3" :activeCard="active_card" :inactiveCards="inactive_cards"
                            :person="personal_information.full_name" :company="working_information.company_name"/>
                <!-- Content for the tab number 4 -->
                <ps-documentation v-show="tab === 4"/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'ps-personal-information': require('./partials/PersonalInformation.vue'),
            'ps-working-information': require('./partials/WorkingInformation.vue'),
            'ps-vehicles': require('./partials/Vehicles.vue'),
            'ps-cards': require('./partials/Cards.vue'),
            'ps-documentation': require('./partials/Documentation.vue'),
        },
        props: {
            personjson: {
                required: true
            }
        },
        data: function() {
            let person_info = JSON.parse(this.personjson);
            return {
                tab: 0,
                edit_route: person_info.edit_url,
                personal_information: person_info.personal_information,
                working_information: person_info.working_information,
                vehicles: person_info.vehicles,
                active_card: person_info.active_card,
                inactive_cards: person_info.inactive_cards
            };
        }
    }
</script>
