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
                        <input type="text" name="last_name" class="form-control"
                               v-model="values.last_name">
                    </div>
                </form-item>
                <form-item col="col-6" label="Nombre" :errors="errors.name">
                    <div class="col">
                        <input type="text" name="name" class="form-control"
                               v-model="values.name">
                    </div>
                </form-item>
            </div>
            <!-- Document & Cuil/Cuit -->
            <div class="form-row">
                <form-item col="col-6" label="Documento" :errors="document_errors">
                    <div class="col-5">
                        <select name="document_type" class="form-control" v-model="values.document_type">
                            <option value="" hidden>Tipo</option>
                            <option value="1">DNI</option>
                            <option value="2">Pasaporte</option>
                        </select>
                    </div>
                    <div class="col-7">
                        <input  type="text" name="document_number" class="form-control"
                                placeholder="Número" v-model="values.document_number">                                                                        
                    </div>
                </form-item>
                <form-item col="col-6" label="CUIL / CUIT" :errors="errors.cuil">
                    <div class="col">
                        <input  type="text" name="cuil" class="form-control"
                                v-model="values.cuil">
                    </div>
                </form-item>
            </div>
            <!-- Birthday, sex & blood type -->
            <div class="form-row">
                <form-item col="col-6" label="Fecha de nacimiento" :errors="errors.birthday">
                    <div class="col">
                        <input type="date" name="birthday" class="form-control"
                               v-model="values.birthday">
                    </div>
                </form-item>
                <form-item col="col-3" label="Género" :errors="errors.sex">
                    <div class="col">
                        <select name="sex" class="form-control" v-model="values.sex">
                            <option value="" hidden>Género</option>
                            <option value="F">Femenino</option>
                            <option value="M">Masculino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                </form-item>
                <form-item col="col-3" label="Grupo Sanguíneo" :errors="errors.blood_type">
                    <div class="col">
                        <select name="blood_type" class="form-control" v-model="values.blood_type">
                            <option value="" hidden>Grupo y factor</option>
                            <option value="0">0-</option>
                            <option value="0">0+</option>
                            <option value="A">A-</option>
                            <option value="A">A+</option>
                            <option value="B">B-</option>
                            <option value="B">B+</option>
                            <option value="AB">AB-</option>
                            <option value="AB">AB+</option>
                        </select>
                    </div>
                </form-item>
            </div>
            <!-- PNA & email -->
            <div class="form-row">
                <form-item col="col-6" label="Prontuario PNA" :errors="errors.pna">
                    <div class="col">
                        <input type="text" name="pna" class="form-control"
                               v-model="values.pna">
                    </div>
                </form-item>
                <form-item col="col-6" label="Email" :errors="errors.email">
                    <div class="col">
                        <input  type="email" name="email" class="form-control"
                                v-model="values.email">
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
                <input type="text" name="home_phone" class="form-control"
                       v-model="values.home_phone">
            </div>
        </form-item>
        <form-item col="col-4" label="Teléfono móvil" :errors="errors.mobile_phone">
            <div class="col">
                <input type="text" name="mobile_phone" class="form-control"
                       v-model="values.mobile_phone">                                
            </div>            
        </form-item>
        <form-item col="col-4" label="FAX" :errors="errors.fax">
            <div class="col">
                <input type="text" name="fax" class="form-control"
                       v-model="values.fax">
            </div>
        </form-item>   
    </div>
    <hr>
    <!-- Street, apartment & zip code -->
    <div class="form-row">
        <form-item col="col-8" label="Domicilio" :errors="direction_errors">
            <div class="col-6">
                <input type="text" name="street" class="form-control"
                       placeholder="Calle y número" v-model="values.street">
            </div>
            <div class="col-6">
                <input type="text" name="apartment" class="form-control"
                       placeholder="Departamento" v-model="values.apartment">
            </div>
        </form-item>
        <form-item col="col-4" label="Código Postal" :errors="errors.cp">
            <div class="col">
                <input type="text" name="cp" class="form-control"
                       v-model="values.cp">                                                                    
            </div>
        </form-item>           
    </div>
    <!-- Country, Province & City -->
    <div class="form-row">
        <form-item col="col-4" label="País" :errors="errors.country">
            <div class="col">
                <select name="country" class="form-control" v-model="values.country">
                    <option value="" hidden>Seleccione país</option>
                    <option value="Argentina">Argentina</option>
                </select>
            </div>
        </form-item>
        <form-item col="col-4" label="Provincia / Estado" :errors="errors.province">
            <div class="col">
                <select name="province" class="form-control" v-model="values.province">
                    <option value="" hidden>Seleccione provincia / estado</option>
                    <option value="Buenos Aires">Buenos Aires</option>
                </select>
            </div>
        </form-item>
        <form-item col="col-4" label="Ciudad" :errors="errors.city">
            <div class="col">
                <select name="city" class="form-control" v-model="values.city">
                    <option value="" hidden>Seleccione ciudad</option>
                    <option value="Mar del Plata">Mar del Plata</option>
                </select>
            </div>
        </form-item>          
    </div>
</div>
</template>

<script>
export default {
    data: () => {
        return {
            errors: {},
            values: {
                last_name: '',
                name: '',
                document_type: '',
                document_number: '',                
                cuil: '',
                birthday: '',
                sex: '',
                blood_type: '',
                pna: '',
                email: '',
                home_phone: '',
                mobile_phone: '',
                fax: '',
                street: '',
                apartment: '',
                cp: '',
                country: '',
                province: '',
                city: '',
            }
        };
    },
    methods: {
        save: function() {
            axios.post('person-creation/personal-information', this.values)
                 .then(response => {
                     this.errors = {};
                     this.$parent.$emit('personal-information-saved', true);
                 })
                 .catch(response => {
                     this.errors = response.response.data.errors;
                     this.$parent.$emit('personal-information-saved', false);
                 })
        }
    },
    computed: {
        name:      function() { return this.values.name },
        last_name: function() { return this.values.last_name },
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
    },
    watch: {
        name:      function() { this.$parent.$emit('name-changed', this.name) },
        last_name: function() { this.$parent.$emit('last-name-changed', this.last_name) },
    }
}
</script>

