<style lang="scss" scoped>
    .card + .card { margin-top: 0px }
</style>

<template>
    <div class="row">
        <!-- Left Panel -->
        <div class="col-12 col-xl-5 mb-3">
            <label>Administrar acceso:</label>
            <div class="accordion" id="leftAccordion">
                <!-- Gates -->
                <accordion-item title="Entradas" accordion-id="leftAccordion" active>
                    <gates-settings :loading="gates.updating" :gates="gates.list"/>
                </accordion-item>
                <!-- Required documentation for access -->
                <accordion-item title="Mínima documentación requerida por defecto" accordion-id="leftAccordion">
                    <required-documentation-settings/>
                </accordion-item>
            </div>
        </div>
        <!-- Right Panel -->
        <div class="col-12 col-xl-7">
            <label>Administrar listas:</label>
            <div class="accordion" id="rightAccordion">
                <!-- Vehicles types -->
                <accordion-item title="Tipos de vehículos" accordion-id="rightAccordion" active>
                    <vehicle-types-settings :loading="vehicle_types.updating" :vehicle-types="vehicle_types.list"/>
                </accordion-item>
                <!-- Activities -->
                <accordion-item title="Actividades" accordion-id="rightAccordion">
                    <activities-settings :loading="activities.updating" :activities="activities.list"/>
                </accordion-item>
                <!-- Subactivities -->
                <accordion-item title="Subactividades" accordion-id="rightAccordion">
                    <subactivities-settings :loading="activities.updating || subactivities.updating" :activities="activities.list" :subactivities="subactivities.list"/>
                </accordion-item>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'accordion-item':                   require('./partials/AccordionItem'),
        'gates-settings':                   require('./partials/Gates.vue'),
        'activities-settings':              require('./partials/Activities.vue'),
        'subactivities-settings':           require('./partials/Subactivities.vue'),
        'vehicle-types-settings':           require('./partials/VehicleTypes.vue'),
        'required-documentation-settings':  require('./partials/RequiredDocumentation.vue'),
    },
    mounted() {
        this.$store.dispatch('fetchList', 'gates');
        this.$store.dispatch('fetchList', 'activities');
        this.$store.dispatch('fetchList', 'vehicle_types');
        this.$store.dispatch('fetchList', 'subactivities');
    },
    computed: {
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
