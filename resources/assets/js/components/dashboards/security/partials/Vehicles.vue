<style lang="scss" scoped>
    div.vehicle-card {
        background-color: whitesmoke;
        border: 1px solid rgb(215, 215, 215);
        border-radius: 4px;
        padding: 1em;
        padding-left: 1.5em;
        cursor: pointer;
        & > h6 { font-weight: normal }
        &:hover { background-color: rgb(230, 230, 230) }
        &.selected {
            & > h6 {
                font-weight: bold;
            }
            background-color: #4285F4;
            border-color: rgb(61, 123, 223);
            color: white;
            &:hover { background-color: rgb(61, 123, 223) }
        }
    }
</style>


<template>
    <div>
        <div v-if="vehicles_filtered.length" class="row">
            <div v-for="(vehicle, key) in vehicles_filtered" :key="key" class="col-3">
                <div :class="`vehicle-card ${vehicle.id === selected ? 'selected' : ''}`" @click="selectVehicle(vehicle.id)">
                    <i v-if="vehicle.id === selected" class="fas fa-check-circle float-right"></i>
                    <i v-else class="far fa-circle float-right"></i>
                    <h6>{{ vehicle.type }}</h6>
                    <h6>{{ vehicle.plate }}</h6>
                    <h6>{{ vehicle.brand + ', ' + vehicle.model }}</h6>
                    <h6>{{ vehicle.year }}</h6>
                    <h6>{{ vehicle.colour }}</h6>
                </div>
            </div>
        </div>
        <h4 v-else class="text-center">No hay nada para mostrar</h4>
    </div>
</template>

<script>
export default {
    props: {
        vehicles: {
            type: Array,
            required: true
        },
        filter: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            selected: null
        };
    },
    computed: {
        vehicles_filtered: function() {
            let search = this.filter;
            return this.vehicles.filter(({plate, brand, model, type, colour, year}) => {
                return  plate.matches(search) || brand.matches(search) || model.matches(search) ||
                        type.matches(search) || colour.matches(search) || year.toString().matches(search);
            });
        }
    },
    methods: {
        selectVehicle: function(id) {
            this.selected = id;
            this.$emit('selection', id);
        }
    }
}
</script>
