<template>
    <div>
        <div class="form-row">
            <!-- Series number -->
            <form-item col="col-4" label="Número de serie" :errors="errors.series_number">
                <div class="col">
                    <input type="text" name="series_number" class="form-control" :value="values.series_number" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <!-- Format -->
            <form-item col="col-4" label="Formato" :errors="errors.format">
                <div class="col">
                    <input type="text" name="format" class="form-control" :value="values.format" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <!-- Size -->
            <form-item col="col-4" label="Tamaño" :errors="errors.size">
                <div class="col">
                    <input type="text" name="size" class="form-control" :value="values.size" @input="(e) => update(e.target)">
                </div>
            </form-item>
        </div>
        <div class="form-row">
            <!-- Brand -->
            <form-item col="col-4" label="Marca" :errors="errors.brand">
                <div class="col">
                    <input type="text" name="brand" class="form-control" :value="values.brand" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <!-- Model -->
            <form-item col="col-4" label="Modelo" :errors="errors.model">
                <div class="col">
                    <input type="text" name="model" class="form-control" :value="values.model" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <!-- Colour -->
            <form-item col="col-4" label="Color" :errors="errors.colour">
                <div class="col">
                    <input type="text" name="colour" class="form-control" :value="values.colour" @input="(e) => update(e.target)">
                </div>
            </form-item>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        values: {
            required: true,
            type: Object,
        },
        errors: {
            required: true,
            type: Array,
        }
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'container', properties_path: `values.general_information.${name}`, value: value });
        }
    },
}
</script>
