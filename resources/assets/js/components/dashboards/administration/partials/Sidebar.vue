<style lang="scss" scoped>
    %btn-basic {
        display: block;
        text-decoration: none;
        padding: .5em 0 .5em 1.5em;
        transition: background-color .3s;
        &:hover {
            background-color: rgba(28, 35, 49, .3);
            cursor: pointer;
        }
        &.router-link-exact-active {
            background-color: rgba(28, 35, 49, .6);
            cursor: default;
            border-left: 5px solid white;
            padding-left: calc(1em + 50px - 5px);
        }
    }

    div.sidebar {
        // Puts the sidebar over eveithing but the navbar
        z-index: 9;
        // Position
        position: absolute;
        top: 0; bottom: 0;
        width: 210px;
        // Colors and padding
        padding: 1em 0;
        & * { color: rgba(255, 255, 255, 0.9) }
        background-color: rgba(28, 35, 49, 0.8);
        // Overflow
        overflow-x: auto;
        font-weight: bold;

        & > div.sidebar-body {
            // max-height: 90%;
            // height: 90%;
            // overflow-y: auto;

            & > div.sidebar-group {

                & > a.sidebar-toggle {
                    @extend %btn-basic;
                    font-weight: bold;
                }

                & > a.btn-sidebar.sidebar-toggle { 
                    & > i.toggle-icon {
                        float: right;
                        padding-top: .4em;
                    }
                }

                & > div.items {
                    & > a.btn-sidebar {
                        @extend %btn-basic;
                        padding-left: calc(1em + 50px);
                    }
                }
            }
        }
    }

    .sidebar-enter, .sidebar-leave-to { transform: translateX(-250px) }
    .sidebar-enter-active, .sidebar-leave-active  { transition: all .5s }
</style>

<template>
    <transition name="sidebar">
        <div class="sidebar">
            <!-- Body -->
            <div class="sidebar-body">
                <!-- People -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.people = !group_active.people">
                        <i class="fas centered fa-users fa-lg"></i> Personas
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.people ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.people">
                        <!-- People Index -->
                        <router-link to="/people" class="btn-sidebar">Listado</router-link>
                        <!-- People creation -->
                        <router-link to="/people/create" class="btn-sidebar">
                            {{ this.$store.getters.person.editing ? 'Editar' : 'Crear' }}
                            <i v-if="$store.getters.person.modified" class="fas fa-exclamation centered"></i>
                        </router-link>
                    </div>
                </div>
                <!-- Companies -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.companies = !group_active.companies">
                        <i class="fas centered fa-building fa-lg"></i> Empresas
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.companies ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.companies">
                        <!-- Companies creation -->
                        <router-link to="/companies" class="btn-sidebar">Listado</router-link>
                        <router-link to="/companies/create" class="btn-sidebar">
                            {{ this.$store.getters.company.editing ? 'Editar' : 'Crear' }}
                            <i v-if="$store.getters.company.modified" class="fas fa-exclamation centered"></i>
                        </router-link>
                    </div>
                </div>
                <!-- Vehicles -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.vehicles = !group_active.vehicles">
                        <i class="fas centered fa-car fa-lg"></i> Vehículos
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.vehicles ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.vehicles">
                        <router-link to="/vehicles" class="btn-sidebar">Listado</router-link>
                        <router-link to="/vehicles/create" class="btn-sidebar">
                            {{ this.$store.getters.vehicle.editing ? 'Editar' : 'Crear' }}
                            <i v-if="$store.getters.vehicle.modified" class="fas fa-exclamation centered"></i>
                        </router-link>
                    </div>
                </div>
                <!-- Containers -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.containers = !group_active.containers">
                        <i class="fas centered fa-boxes fa-lg"></i> Contenedores
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.containers ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.containers">
                        <router-link to="/containers" class="btn-sidebar">Listado</router-link>
                        <router-link to="/containers/create" class="btn-sidebar">
                            {{ this.$store.getters.container.editing ? 'Editar' : 'Crear' }}
                            <i v-if="$store.getters.container.modified" class="fas fa-exclamation centered"></i>
                        </router-link>
                    </div>
                </div>
                <!-- Groups -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.groups = !group_active.groups">
                        <i class="fas centered fa-universal-access fa-lg"></i> Grupos
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.groups ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.groups">
                        <router-link to="/groups" class="btn-sidebar">Listado</router-link>
                        <router-link to="/groups/create" class="btn-sidebar">
                            {{ this.$store.getters.groups.editing ? 'Editar' : 'Crear' }}
                            <i v-if="$store.getters.groups.modified" class="fas fa-exclamation centered"></i>
                        </router-link>
                    </div>
                </div>
                <hr>
                <!-- Expiration -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.expiration = !group_active.expiration">
                        <i class="fas centered fa-clock fa-lg"></i> Vencimientos
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.expiration ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.expiration">
                        <router-link to="/bar" class="btn-sidebar">Go to Bar</router-link>
                    </div>
                </div>
                <hr>
                <!-- Settings -->
                <div class="sidebar-group">
                    <a class="btn-sidebar sidebar-toggle" @click="group_active.settings = !group_active.settings">
                        <i class="fas fa-cog centered fa-lg"></i> Configuración
                        <i :class="'toggle-icon fas centered fa-caret-' + (group_active.settings ? 'up' : 'down')"></i>
                    </a>
                    <div class="items" v-if="group_active.settings">
                        <router-link to="/settings" class="btn-sidebar">Sistema</router-link>
                        <router-link to="/bar" class="btn-sidebar">Usuarios</router-link>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            group_active: {
                people:     false,
                companies:  false,
                vehicles:   false,
                containers: false,
                groups:     false,
                expiration: false,
                settings:   false,
            }
        };
    }
}
</script>

