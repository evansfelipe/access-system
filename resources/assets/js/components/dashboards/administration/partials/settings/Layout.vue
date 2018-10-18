<style lang="scss" scoped>
    ul {
        list-style: none;
        border: 0;
        padding: 0;
        & > li {
            text-align: right;
            padding: 0.5em;
            cursor: pointer;
            font-weight: bold;
            color: grey;
            &.active {
                color: black;
            }
        }
    }

    div.card {
        min-height: 100%;
        & > div.card-body {
            padding: .5 3em;
        }
    }
</style>


<template>
    <div class="row">
        <div class="col-2">
            <ul>
                <li :class="`${active === 0 ? 'active' : ''}`" @click="active = 0">Zonas</li>
                <li :class="`${active === 1 ? 'active' : ''}`" @click="active = 1">Puertas</li>
                <li :class="`${active === 2 ? 'active' : ''}`" @click="active = 2">Documentación</li>
                <li :class="`${active === 3 ? 'active' : ''}`" @click="active = 3">Actividades</li>
                <li :class="`${active === 4 ? 'active' : ''}`" @click="active = 4">Subactividades</li>
                <li :class="`${active === 5 ? 'active' : ''}`" @click="active = 5">Tipos de vehículo</li>
            </ul>
        </div>
        <div class="col-10">
            <div class="card card-default">
                <div class="card-header">
                    <small>
                        <span v-if="active === 0">Zonas</span>
                        <span v-if="active === 1">Puertas</span>
                        <span v-if="active === 2">Documentación</span>
                        <span v-if="active === 3">Actividades</span>
                        <span v-if="active === 4">Subactividades</span>
                        <span v-if="active === 5">Tipos de vehículos</span>
                    </small>
                </div>
                <div class="card-body">
                    <zones-settings v-if="active === 0" :loading="zones.updating" :zones="zones.list"/>
                    <gates-settings v-if="active === 1" :loading="gates.updating || zones.updating" :zones="zones.list" :gates="gates.list"/>
                    <required-documentation-settings v-if="active === 2"/>
                    <activities-settings v-if="active === 3" :loading="activities.updating" :activities="activities.list"/>
                    <subactivities-settings v-if="active === 4" :loading="activities.updating || subactivities.updating" :activities="activities.list" :subactivities="subactivities.list"/>
                    <vehicle-types-settings v-if="active === 5" :loading="vehicle_types.updating" :vehicle-types="vehicle_types.list"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'zones-settings':                   require('./partials/Zones.vue'),
        'gates-settings':                   require('./partials/Gates.vue'),
        'activities-settings':              require('./partials/Activities.vue'),
        'vehicle-types-settings':           require('./partials/VehicleTypes.vue'),
        'subactivities-settings':           require('./partials/Subactivities.vue'),
        'required-documentation-settings':  require('./partials/RequiredDocumentation.vue'),
    },
    data() {
        return {
            active: 0,
        };
    },
    mounted() {
        this.$store.dispatch('fetchList', 'zones');
        this.$store.dispatch('fetchList', 'gates');
        this.$store.dispatch('fetchList', 'activities');
        this.$store.dispatch('fetchList', 'vehicle_types');
        this.$store.dispatch('fetchList', 'subactivities');
    },
    computed: {
        zones: function() {
            return {
                list: this.$store.getters.zones.list.concat().sort((a, b) => a.name.matches(b.name)), // As sort mutates the array, we use cocant to clone it.
                updating: this.$store.getters.zones.updating
            };
        },
        gates: function() {
            return {
                list: this.$store.getters.gates.list.concat().sort((a, b) => a.name.matches(b.name)), // As sort mutates the array, we use cocant to clone it.
                updating: this.$store.getters.gates.updating
            };
        },
        activities: function() {
            return {
                list: this.$store.getters.activities.list.concat().sort((a, b) => a.name.matches(b.name)), // As sort mutates the array, we use cocant to clone it.
                updating: this.$store.getters.activities.updating
            };
        },
        subactivities: function() {
            return {
                list: this.$store.getters.subactivities.list.concat().sort((a, b) => a.name.matches(b.name)), // As sort mutates the array, we use cocant to clone it.
                updating: this.$store.getters.subactivities.updating
            };
        },
        vehicle_types: function() {
            return {
                list: this.$store.getters.vehicle_types.list.concat().sort((a, b) => a.type.matches(b.type)), // As sort mutates the array, we use cocant to clone it.
                updating: this.$store.getters.vehicle_types.updating,
            }
        }
    }
}
</script>
