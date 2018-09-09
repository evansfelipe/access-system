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
            <form-item col="col-4" label="País" :errors="errors.country">
                <div class="col">
                    <select2    name="country" placeholder="Seleccione un país" :options="countries"
                                :loading="raw_countries.loading"
                                :value="values.country" :tags="true"
                                @input="(value) => update({name: 'country', value: value})"
                    />
                </div>
            </form-item> 
            <form-item col="col-4" label="Provincia / Estado" :errors="errors.province">
                <div class="col">
                    <select2    name="province" placeholder="Seleccione una provincia/estado" :options="provinces" 
                                :disabled="values.country ? false : true" :loading="raw_provinces.loading"
                                :value="values.province" :tags="true"
                                @input="(value) => update({name: 'province', value: value})"
                    />
                </div>
            </form-item>
            <form-item col="col-4" label="Ciudad" :errors="errors.city">
                <div class="col">
                    <select2    name="city" placeholder="Seleccione una ciudad" :options="cities"
                                :disabled="values.province ? false : true" :loading="raw_cities.loading"
                                :value="values.city" :tags="true"
                                @input="(value) => update({name: 'city', value: value})"
                    />
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
            raw_countries: {
                list: [],
                loading: false
            },
            raw_provinces: {
                list: [],
                loading: false
            },
            raw_cities: {
                list: [],
                loading: false
            }
        }
    },
    mounted() {
        this.raw_countries.loading = true;
        axios.get('locations/countries')
        .then(response => this.raw_countries.list = response.data)
        .catch(error =>   this.raw_countries.list = [])
        .finally(() => this.raw_countries.loading = false);
    },
    computed: {
        countries: function() {
            return this.raw_countries.list.map(country => {
                return {
                    id: country.name,
                    text: country.name
                };
            });
        },
        provinces: function() {
            return this.raw_provinces.list.map(province => {
                return {
                    id: province.name,
                    text: province.name
                };
            });
        },
        cities: function() {
            return this.raw_cities.list.map(city => {
                return {
                    id: city.name,
                    text: city.name
                };
            });
        }
    },
    methods: {
        update: function({name, value}) {
            this.$emit('input', {name, value});
        }
    },
    watch: {
        'values.country': function() {
            // Removes the selected values for province and city because they will have no sense when the list change.
            this.update({name: 'province', value: ''});
            this.update({name: 'city', value: ''});
            // Gets the country associated with the selected country value.
            let country = this.raw_countries.list.filter(country => country.name === this.values.country)[0];
            // If there is a country associated with the selected value, then requests the list of provinces of this country.
            this.raw_provinces.list = [];
            if(country) {
                this.raw_provinces.loading = true;
                axios.get(`locations/provinces/${country.id}`)
                .then(response => this.raw_provinces.list = response.data)
                .catch(error => console.log(error))
                .finally(() => this.raw_provinces.loading = false);
            }
        },
        'values.province': function() {
            // Removes the selected value for the city because it will have no sense when the list change.
            this.update({name: 'city', value: ''});
            // Gets the province associated with the selected province value.
            let province = this.raw_provinces.list.filter(province => province.name === this.values.province)[0];
            // If there is a province associated with the selected value, then requests the list of cities of this province.
            this.raw_cities.list = [];
            if(province) {
                this.raw_cities.loading = true
                axios.get(`locations/cities/${province.id}`)
                .then(response => this.raw_cities.list = response.data)
                .catch(error => console.log(error))
                .finally(() => this.raw_cities.loading = false);
            }
        }
    }
}
</script>
