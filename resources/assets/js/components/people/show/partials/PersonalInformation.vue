<style lang="scss" scoped>
    small { display: block; }
    div.row + div.row { margin-top: 15px }
</style>

<template>
    <div> <!-- Prevents d-flex problem with v-show -->
        <div class="row d-flex align-items-center">
            <div class="col-md-4 text-center">
                <div class="offset-1 col-10">
                    <img class="img-fluid rounded-circle shadow-sm" :src="person.picture">
                    <br>
                    <button class="btn btn-link mt-2" @click="showPictures">
                        <i class="fas fa-images"></i> Ver más
                    </button>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Last name & name -->
                <h3 class="mb-3">{{ person.full_name }}</h3>
                <!-- document, cuil & birthday -->
                <div class="row">
                    <div class="col-4">
                        <small>{{ person.document_type }}</small>
                        <strong>{{ person.document_number }}</strong>
                    </div>
                    <div class="col-4">
                        <small>CUIL / CUIT</small>
                        <strong>{{ person.cuil }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Fecha de nacimiento</small>
                        <strong>{{ person.birthday }}</strong>
                    </div>
                </div>
                <!-- Sex, blood type & homeland -->
                <div class="row">
                    <div class="col-4">
                        <small>Género</small>
                        <strong>{{ person.sex }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Grupo y factor sanguíneo</small>
                        <strong>{{ person.blood_type }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Nacionalidad</small>
                        <strong>{{ person.homeland }}</strong>
                    </div>
                </div>
                <!-- Risk, register number & PNA -->
                <div class="row">
                    <div class="col-4">
                        <small>Nivel de riesgo</small>
                        <strong>{{ person.risk }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Legajo</small>
                        <strong>{{ person.register_number }}</strong>
                    </div>

                    <div class="col-4">
                        <small>Prontuario PNA</small>
                        <strong>{{ person.pna }}</strong>
                    </div>
                </div>
                <!-- Email -->
                <div class="row">
                    <div class="col">
                        <small>Email</small>
                        <strong>{{ person.email }}</strong>
                    </div>
                </div>
                <!-- Phone, mobile phone & fax -->
                <div class="row">
                    <div class="col-4">
                        <small>Teléfono fijo</small>
                        <strong>{{ person.phone }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Teléfono móvil</small>
                        <strong>{{ person.mobile_phone }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Fax</small>
                        <strong>{{ person.fax }}</strong>
                    </div>
                </div>
                <!-- Address, apartment & cp -->
                <div class="row">
                    <div class="col-4">
                        <small>Dirección</small>
                        <strong>{{ person.street }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Departamento</small>
                        <strong>{{ person.apartment }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Código Postal</small>
                        <strong>{{ person.cp }}</strong>
                    </div>
                </div>
                <!-- City, province & country -->
                <div class="row">
                    <div class="col-4">
                        <small>Ciudad</small>
                        <strong>{{ person.city }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Provincia / Estado</small>
                        <strong>{{ person.province }}</strong>
                    </div>
                    <div class="col-4">
                        <small>País</small>
                        <strong>{{ person.country }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pictures panel -->
        <modal-wrapper :visible="pictures.opened" @closed="pictures.opened = false" :title="`Imágenes de ${person.full_name}`">
            <loading-cover v-if="pictures.loading"/>
            <pictures-gallery :links="pictures.list"/>
        </modal-wrapper>
    </div>
</template>

<script>
export default {
    props: {
        person: {
            type:     Object,
            required: true
        }
    },
    data() {
        return {
            pictures: {
                list: [],
                loading: false,
                opened:  false,
            }
        }
    },
    methods: {
        showPictures: function() {
            this.pictures.opened = true;
            this.pictures.loading = true;
            axios.get(`people/${this.$route.params.id }/pictures`)
            .then(response => {
                this.pictures.list = response.data;
                this.pictures.loading = false;
            })
            .catch(error => console.log(error))
        }
    }
}
</script>


