<template>
<div>
    <div class="form-row d-flex align-items-center">
        <!-- Photo loader section -->
        <div class="col-4 text-center">
            <div class="offset-1 col-10">
                <img class="img-fluid rounded-circle" src="/pictures/no-image.jpg" alt="Subir imagen">
            </div>
        </div>
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
                        <input  type="text" name="cuil" class="form-control" placeholder="xx-xxxxxxxx-x" :value="values.cuil" @input="(e) => update(e.target)">
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
                        <select2 placeholder="Grupo y factor" name="blood_type" class="form-control" :value="values.blood_type" @input="(value) => update({name: 'blood_type', value: value})" :options="options.blood_type"/>
                    </div>
                </form-item>
            </div>
            <!-- PNA & email -->
            <div class="form-row">
                <form-item col="col-6" label="Prontuario PNA" :errors="errors.pna">
                    <div class="col">
                        <input type="text" name="pna" class="form-control" :value="values.pna" @input="(e) => update(e.target)">
                    </div>
                </form-item>
                <form-item col="col-6" label="Email" :errors="errors.email">
                    <div class="col">
                        <input  type="email" name="email" class="form-control" :value="values.email" @input="(e) => update(e.target)">
                    </div>                    
                </form-item>
            </div>
        </div>
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
        <form-item col="col-4" label="FAX" :errors="errors.fax">
            <div class="col">
                <input type="text" name="fax" class="form-control" :value="values.fax" @input="(e) => update(e.target)">
            </div>
        </form-item>   
    </div>
    <hr>
    <!-- Street, apartment & zip code -->
    <div class="form-row">
        <form-item col="col-8" label="Domicilio" :errors="direction_errors">
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
                <select2    name="country" :value="values.country" @input="(value) => update({name: 'country', value: value})"
                            placeholder="Seleccione un país" :options="options.country" :tags="true"></select2>                                
            </div>
        </form-item>
        <form-item col="col-4" label="Provincia / Estado" :errors="errors.province">
            <div class="col">
                <select2    name="province" :value="values.province" @input="(value) => update({name: 'province', value: value})"
                            placeholder="Seleccione una provincia/estado" :options="options.province" :tags="true"></select2>
            </div>
        </form-item>
        <form-item col="col-4" label="Ciudad" :errors="errors.city">
            <div class="col">
                <select2    name="city" :value="values.city" @input="(value) => update({name: 'city', value: value})"
                            placeholder="Seleccione una ciudad" :options="options.city" :tags="true"></select2>
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
            type: Object,
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
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.personal_information.${name}`, value: value });
        },
    },
    computed: {
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
        },
        direction_errors: function() {
            if(this.errors.street && this.errors.apartment) {
                return this.errors.street.concat(this.errors.apartment);
            }
            else if(this.errors.street) {
                return this.errors.street;
            }
            else if(this.errors.apartment) {
                return this.errors.apartment
            }
            else {
                return [];
            }
        },
    }
}
</script>

