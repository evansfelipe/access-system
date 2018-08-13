<template>
    <div> <!-- Prevents d-flex problem with v-show -->
        <div class="row d-flex align-items-center">
            <div class="col-md-4 text-center">
                <div class="offset-1 col-10">
                    <img class="img-fluid rounded-circle shadow-sm" :src="person.picture_path">
                    <br>
                    <button class="btn mt-2 btn-sm btn-outline-info" @click="showPictures">Ver más</button>
                </div>
            </div>
            <div class="col-md-8">
                <h3>{{ person.full_name }}</h3>
                <br>
                <div class="row">
                    <div class="col-4">
                        <small>Legajo</small>
                        <br>
                        <strong>{{ person.record }}</strong>
                    </div>
                    <div class="col-4">
                        <small>{{ person.document_type }}</small>
                        <br>
                        <strong>{{ person.document_number }}</strong>
                    </div>
                    <div class="col-4">
                        <small>CUIL / CUIT</small>
                        <br>
                        <strong>{{ person.cuil }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <small>Fecha de nacimiento</small>
                        <br>
                        <strong>{{ person.birthday }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Nacionalidad</small>
                        <br>
                        <strong>{{ person.birth_country }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Género</small>
                        <br>
                        <strong>{{ person.sex }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <small>Grupo y factor sanguíneo</small>
                        <br>
                        <strong>{{ person.blood_type }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Prontuario PNA</small>
                        <br>
                        <strong>{{ person.pna }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Email</small>
                        <br>
                        <strong>{{ person.email }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <small>Teléfono fijo</small>
                        <br>
                        <strong>{{ person.phone }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Teléfono móvil</small>
                        <br>
                        <strong>{{ person.mobile_phone }}</strong>
                    </div>
                    <div class="col-4">
                        <small>FAX</small>
                        <br>
                        <strong>{{ person.fax }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <small>Dirección</small>
                        <br>
                        <strong>{{ person.address }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Departmaneto</small>
                        <br>
                        <strong>{{ person.apartment }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Código Postal</small>
                        <br>
                        <strong>{{ person.cp }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <small>Ciudad</small>
                        <br>
                        <strong>{{ person.city }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Provincia / Estado</small>
                        <br>
                        <strong>{{ person.province }}</strong>
                    </div>
                    <div class="col-4">
                        <small>País</small>
                        <br>
                        <strong>{{ person.country }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pictures panel -->
        <modal-wrapper :visible="pictures.opened" @closed="pictures.opened = false">
            <loading-cover v-if="pictures.loading" message="Cargando..."/>
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div v-for="(picture, key) in pictures.list" :key="key" :class="`carousel-item ${key === 0 ? 'active' : ''}`">
                        <img class="d-block w-100" :src="picture" alt="First slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </modal-wrapper>
    </div>
</template>

<script>
export default {
    props: {
        person: {
            required: true,
            type: Object
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


