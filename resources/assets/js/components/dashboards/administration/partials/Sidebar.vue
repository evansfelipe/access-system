<style lang="scss" scoped>

    %btn-basic {
        display: block;
        text-decoration: none;
        padding: .5em 0 .5em 1em;
        &:hover {
            background-color: #e3eaf0;
            cursor: pointer;
        }
        &.router-link-exact-active {
            background-color: #dce3e7;
            color: black;
            cursor: default;
            font-weight: bold;
        }
    }

    div.sidebar {
        // Puts the sidebar over eveithing but the navbar
        z-index: 9;
        // Right shadow
        box-shadow: 3px 0 5px -2px rgba(0,0,0,.175);
        // Position
        position: absolute;
        top: 0; bottom: 0; left: 0; right: 85vw;
        // Colors and padding
        padding: 1em 0;
        & * { color: grey }
        background-color: #ebf0f3;
        // Overflow
        overflow-x: auto;

        & > div.sidebar-group {
            & > a.sidebar-toggle {
                @extend %btn-basic;
                font-weight: bold;
            }

            & > a.btn-sidebar.sidebar-toggle { 
                color: black;
                & > i.toggle-icon {
                    float: right;
                    padding-top: .4em;
                }
            }

            & > div.items {
                visibility: visible;
                & > a.btn-sidebar {
                    @extend %btn-basic;
                    padding-left: calc(1em + 50px);
                }
            }

            &.closed  > div.items {
                visibility: hidden;
                max-height: 0;
            }
        }
    }
</style>

<template>
    <div class="sidebar">
        <!-- People -->
        <div :class="'sidebar-group ' + (!group_active.people ? 'closed' : '')">
            <a class="btn-sidebar sidebar-toggle" @click="group_active.people = !group_active.people">
                <i class="fas centered fa-users fa-lg"></i> Personas
                <i :class="'toggle-icon fas centered fa-caret-' + (group_active.people ? 'up' : 'down')"></i>
            </a>
            <div class="items">
                <!-- People Index -->
                <router-link to="/people" class="btn-sidebar">Listado</router-link>
                <!-- People creation -->
                <router-link to="/people/create" class="btn-sidebar">Crear</router-link>
            </div>
        </div>
        <!-- Companies -->
        <div :class="'sidebar-group ' + (!group_active.companies ? 'closed' : '')">
            <a class="btn-sidebar sidebar-toggle" @click="group_active.companies = !group_active.companies">
                <i class="fas centered fa-building fa-lg"></i> Empresas
                <i :class="'toggle-icon fas centered fa-caret-' + (group_active.companies ? 'up' : 'down')"></i>
            </a>
            <div class="items">
                <!-- Companies creation -->
                <router-link to="/companies/create" class="btn-sidebar">Crear</router-link>
            </div>
        </div>
        <!-- Vehicles -->
        <div :class="'sidebar-group ' + (!group_active.vehicles ? 'closed' : '')">
            <a class="btn-sidebar sidebar-toggle" @click="group_active.vehicles = !group_active.vehicles">
                <i class="fas centered fa-car fa-lg"></i> Vehículos
                <i :class="'toggle-icon fas centered fa-caret-' + (group_active.vehicles ? 'up' : 'down')"></i>
            </a>
            <div class="items">
                <router-link to="/bar" class="btn-sidebar">Go to Bar</router-link>
            </div>
        </div>

        <hr>

        <!-- Expiration -->
        <div :class="'sidebar-group ' + (!group_active.expiration ? 'closed' : '')">
            <a class="btn-sidebar sidebar-toggle" @click="group_active.expiration = !group_active.expiration">
                <i class="fas centered fa-clock fa-lg"></i> Vencimientos
                <i :class="'toggle-icon fas centered fa-caret-' + (group_active.expiration ? 'up' : 'down')"></i>
            </a>
            <div class="items">
                <router-link to="/bar" class="btn-sidebar">Go to Bar</router-link>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            group_active: {
                people: false,
                companies: false,
                vehicles: false,
                expiration: false,
            }
        };
    }
}
</script>

