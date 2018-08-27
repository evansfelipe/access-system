<template>
    <div>
        <!-- Street, apartment & zip code -->
        <div class="form-row">
            <form-item col="col-8" label="Domicilio" :errors="errors.direction">
                <div class="col-6">
                    <input type="text" name="street" class="form-control" placeholder="Calle y número" :value="values.street" @input="(e) => update(e.target)">
                </div>
                <div class="col-6">
                    <input type="text" name="apartment" class="form-control" placeholder="Departamento" :value="values.apartment" @input="(e) => update(e.target)">
                </div>
            </form-item>
            <form-item col="col-4" label="Código Postal" :errors="errors.cp">
                <div class="col">
                    <input type="text" name="cp" class="form-control" :value="values.cp" @input="(e) => update(e.target)">                                                                    
                </div>
            </form-item>           
        </div>
        <!-- Country, Province & City -->
        <div class="form-row">
            <form-item col="col-4" label="Ciudad" :errors="errors.city">
                <div class="col">
                    <select2    name="city" :value="values.city" @input="(value) => update({name: 'city', value: value})"
                                placeholder="Seleccione una ciudad" :options="options.city" :tags="true"/>
                </div>
            </form-item>
            <form-item col="col-4" label="Provincia / Estado" :errors="errors.province">
                <div class="col">
                    <select2    name="province" :value="values.province" @input="(value) => update({name: 'province', value: value})"
                                placeholder="Seleccione una provincia/estado" :options="options.province" :tags="true"/>
                </div>
            </form-item>
            <form-item col="col-4" label="País" :errors="errors.country">
                <div class="col">
                    <select2    name="country" :value="values.country" @input="(value) => update({name: 'country', value: value})"
                                placeholder="Seleccione un país" :options="options.country" :tags="true"/>                                
                </div>
            </form-item>          
        </div>
    </div>
</template>

<script>
export default {
    props: {
        values: {
            type: Object,
            required: true
        },
        errors: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            options: {
                country: [
                    {id: 0, text: 'Argentina'},
                    {id: 1, text: 'Brasil'}                    
                ],
                province: [
                    {id: 0, text: 'Buenos Aires'},
                    {id: 1, text: 'Salta'}                    
                ],
                city: [
                    {id: 0, text: 'Mar del Plata'},
                    {id: 1, text: 'Balcarce'}                    
                ]
            },
        }
    },
    methods: {
        update: function({name, value}) {
            this.$emit('input', {name, value});
        }
    }
}
</script>
