<template>
<div>
    <div class="form-row d-flex align-items-center">
        <!-- Photo loader section -->
        <form-item col="col-4" :errors="errors.picture">
            <web-camera @pictureTaken="picture => update({ name: 'picture', value: picture })" :img="values.picture_path"/>
        </form-item>
        <!-- Identification information section -->
        <div class="col-8">
            <!-- Last name & name -->
            <div class="form-row">
                <form-item col="col-6" label="Apellido" :errors="errors.last_name">
                    <div class="col">
                        <input type="text" name="last_name" class="form-control" :value="values.last_name" @input="(e) => update(e.target)">
                    </div>
                </form-item>
                <form-item col="col-6" label="Nombre" :errors="errors.name">
                    <div class="col">
                        <input type="text" name="name" class="form-control" :value="values.name" @input="(e) => update(e.target)">
                    </div>
                </form-item>
            </div>
            <!-- Document & Cuil/Cuit -->
            <div class="form-row">
                <form-item col="col-6" label="Documento" :errors="document_errors">
                    <div class="col-5">
                        <select2 placeholder="Tipo" name="document_type" :value="values.document_type" @input="(value) => update({name: 'document_type', value: value})" :options="options.document_type"/>
                    </div>
                    <div class="col-7">
                        <input  type="text" name="document_number" class="form-control" placeholder="Número" :value="values.document_number" @input="(e) => update(e.target)">                                                                        
                    </div>
                </form-item>
                <form-item col="col-6" label="CUIL / CUIT" :errors="errors.cuil">
                    <div class="col">
                        <cuil-cuit :value="values.cuil" @input="(value) => update({name: 'cuil', value: value})"/>
                    </div>
                </form-item>
            </div>
            <!-- Birthday, sex & blood type -->
            <div class="form-row">
                <form-item col="col-6" label="Fecha de nacimiento" :errors="errors.birthday">
                    <div class="col">
                        <input type="date" name="birthday" class="form-control" :value="values.birthday" @input="(e) => update(e.target)">
                    </div>
                </form-item>
                <form-item col="col-3" label="Género" :errors="errors.sex">
                    <div class="col">
                        <select2    name="sex" :value="values.sex" @input="(value) => update({name: 'sex', value: value})" ref="sex"
                                    :options="options.sex"/>
                    </div>
                </form-item>
                <form-item col="col-3" label="Grupo Sanguíneo" :errors="errors.blood_type">
                    <div class="col">
                        <select2 placeholder="Grupo y factor" name="blood_type" :value="values.blood_type" @input="(value) => update({name: 'blood_type', value: value})" :options="options.blood_type"/>
                    </div>
                </form-item>
            </div>
            <!-- PNA & email -->
            <div class="form-row">
                <form-item col="col-6" label="Nacionalidad" :errors="errors.homeland">
                    <div class="col">
                        <select2    :tags="true" name="homeland" :value="values.homeland" @input="(value) => update({name: 'homeland', value: value})"
                                    :options="options.homeland"/>
                    </div>
                </form-item>
                <form-item col="col-6" label="Nivel de riesgo" :errors="errors.risk">
                    <div class="col">
                        <select2    name="risk" :value="values.risk" @input="(value) => update({name: 'risk', value: value})"
                                    placeholder="Seleccione un nivel de riesgo" :options="options.risk_levels"/>
                    </div>
                </form-item>
            </div>
        </div>
    </div>
    <div class="form-row">
        <form-item col="col-4" label="Nº Legajo" :errors="errors.register_number">
            <div class="col">
                <input type="text" name="register_number" class="form-control" :value="values.register_number" @input="(e) => update(e.target)">
            </div>
        </form-item>
        <form-item col="col-4" label="Nº Prontuario PNA" :errors="errors.pna">
            <div class="col">
                <input type="text" name="pna" class="form-control" :value="values.pna" @input="(e) => update(e.target)">
            </div>
        </form-item>
        <form-item col="col-4" label="Email" :errors="errors.email">
            <div class="col">
                <input  type="email" name="email" class="form-control" :value="values.email" @input="(e) => update(e.target)">
            </div>                    
        </form-item>
    </div>
    <hr>
    <!-- Home phone, mobile phone & fax -->
    <div class="form-row">
        <form-item col="col-4" label="Teléfono fijo" :errors="errors.home_phone">
            <div class="col">
                <input type="text" name="home_phone" class="form-control" :value="values.home_phone" @input="(e) => update(e.target)">
            </div>
        </form-item>
        <form-item col="col-4" label="Teléfono móvil" :errors="errors.mobile_phone">
            <div class="col">
                <input type="text" name="mobile_phone" class="form-control" :value="values.mobile_phone" @input="(e) => update(e.target)">
            </div>            
        </form-item>
        <form-item col="col-4" label="Fax" :errors="errors.fax">
            <div class="col">
                <input type="text" name="fax" class="form-control" :value="values.fax" @input="(e) => update(e.target)">
            </div>
        </form-item>   
    </div>
    <hr>
    <residency-input :values="residency_values" :errors="residency_errors" @input="v => update(v)"/>
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
    data() {
        return {
            options: {
                sex: [
                    {id: 'F', text: 'Femenino'},
                    {id: 'M', text: 'Masculino'},
                    {id: 'O', text: 'Otro'},
                ],
                document_type: [
                    {id: "0", text: 'DNI'},
                    {id: "1", text: 'Pasaporte'}                    
                ],
                blood_type: [
                    {id: "0-", text: "0-"},
                    {id: "0+", text: "0+"},
                    {id: "A-", text: "A-"},
                    {id: "A+", text: "A+"},
                    {id: "B-", text: "B-"},
                    {id: "B+", text: "B+"},
                    {id: "AB-", text: "AB-"},
                    {id: "AB+", text: "AB+"},   
                ],
                risk_levels: [
                    { id: '1', text: 'Nivel 1' },
                    { id: '2', text: 'Nivel 2' },
                    { id: '3', text: 'Nivel 3' },
                ],
                homeland: []
            },
        }
    },
    mounted() {
        axios.get('app/locations/countries')
        .then(response => this.options.homeland = response.data.map(country => {
            return {
                id: country.name,
                text: country.name,
            };
        }))
        .catch(error => console.log(error));
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.personal_information.${name}`, value: value });
        }
    },
    computed: {
        residency_values: function() {
            return {
                street: this.values.street,
                apartment: this.values.apartment,
                cp: this.values.cp,
                country: this.values.country,
                province: this.values.province,
                city: this.values.city
            }
        },
        residency_errors: function() {
            let direction = [];
            if(this.errors.street && this.errors.apartment) {
                direction = this.errors.street.concat(this.errors.apartment);
            }
            else if(this.errors.street) {
                direction = this.errors.street;
            }
            else if(this.errors.apartment) {
                direction = this.errors.apartment
            }

            return {
                direction: direction,
                cp: this.errors.cp,
                country: this.errors.country,
                province: this.errors.province,
                city: this.errors.city
            }
        },
        document_errors: function() {
            if(this.errors.document_type && this.errors.document_number) {
                return this.errors.document_type.concat(this.errors.document_number);
            }
            else if(this.errors.document_type) {
                return this.errors.document_type;
            }
            else if(this.errors.document_number) {
                return this.errors.document_number
            }
            else {
                return [];
            }
        }
    }
}
</script>

