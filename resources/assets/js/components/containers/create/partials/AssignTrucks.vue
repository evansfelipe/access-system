<template>
    <div>
        <loading-cover v-if="this.$store.getters.vehicles.updating" message="Cargando..."/>
        <template v-else>
            <custom-table
                :columns="columns"
                :rows="trucks"
                :pickable="{
                    active: true,
                    list: trucks_picked
                }"
                @rowclicked="toggleTruck"
            />
        </template>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            show_outdated: false,
            columns: [ 
                {name: 'plate',        text: 'Patente', width: '15'},
                {name: 'brand',        text: 'Marca',   width: '20'},
                {name: 'model',        text: 'Modelo',  width: '20'},
                {name: 'year',         text: 'Año',     width: '10'},
                {name: 'company_name', text: 'Empresa', width: '35'},
            ]
        };
    },
    beforeMount() {
        this.$store.dispatch('fetchList', 'vehicles');
    },
    computed: {
        vehicles: function() {
            return this.$store.getters.vehicles.list;
        },
        trucks: function() {
            return this.$store.getters.vehicles.list.filter(vehicle => vehicle.allows_container);
        },
        trucks_picked: function() {
            return this.$store.getters.container.values.assign_trucks.trucks_id;
        },
        picked_list: function() {
            let list = [];
            this.trucks_picked.forEach(truck_id => {
                list.push(this.$store.getters.vehicles.getById(truck_id));
            });
            return list;
        }
    },
    methods: {
        unpickAll() {
            this.$store.getters.container.values.assign_trucks.trucks_id.forEach(
                vehicle => this.$store.commit('pickVehicle', { id: vehicle.id, value: false })
            );
        },
        toggleTruck(truck) {
            this.$store.commit('pickTruck', truck.id);
        }
    }
}
</script>

